<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Property
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $property_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PropertyFieldValue[] $propertyFieldValues
 * @property-read int|null $property_field_values_count
 * @method static \Database\Factories\PropertyFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Property newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Property newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Property query()
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property wherePropertyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Property extends Model
{
    use HasFactory;

    const LOOSE_DEVIATION = 1.25;

    public function propertyFieldValues()
    {
        return $this->hasMany(PropertyFieldValue::class, 'property_id', 'id');
    }

    protected function checkRangeStrict(?float $value, ?float $minValue, ?float $maxValue) : bool
    {
        return ($minValue === null || $minValue <= $value)
            && ($maxValue === null || $maxValue >= $value);
    }

    protected function checkRangeLoose(?float $value, ?float $minValue, ?float $maxValue) : bool
    {
        if ($minValue !== null)
            $minValue /= self::LOOSE_DEVIATION;

        if ($maxValue !== null)
            $maxValue *= self::LOOSE_DEVIATION;

        return $this->checkRangeStrict($value, $minValue, $maxValue);
    }

    protected function convertToFloat(mixed $value) : ?float
    {
        return $value === null ? null : floatval($value);
    }

    public function calculateMatch(SearchProfile $searchProfile): array
    {
        $strictMatchesCount = 0;
        $looseMatchesCount = 0;

        foreach($searchProfile->searchProfileValues as $searchProfileValue)
        {
            /** @var PropertyFieldValue $propertyFieldValue */
            $propertyFieldValue = $this->propertyFieldValues->first(fn($v, $k) => $v->property_field_id === $searchProfileValue->property_field_id);

            if ($propertyFieldValue === null)
                continue;

            switch($searchProfileValue->kind)
            {
                case SearchProfileValue::DIRECT:
                    if ($searchProfileValue->direct_value === $propertyFieldValue->value)
                    {
                        $strictMatchesCount++;
                    }
                    break;

                case SearchProfileValue::RANGE:
                    if( $searchProfileValue->min_range_value !== null || $searchProfileValue->max_range_value !== null)
                    {
                        $value = $this->convertToFloat($propertyFieldValue->value);
                        $minRangeValue = $this->convertToFloat($searchProfileValue->min_range_value);
                        $maxRangeValue = $this->convertToFloat($searchProfileValue->max_range_value);

                        if ($this->checkRangeStrict($value, $minRangeValue, $maxRangeValue))
                        {
                            $strictMatchesCount++;
                        }
                        elseif ($this->checkRangeLoose($value, $minRangeValue, $maxRangeValue))
                        {
                            $looseMatchesCount++;
                        }
                    }
                    break;

                default:
                    throw new \InvalidArgumentException('Wrong value for the kind field');
            }
        }

        // Score is a floating-point number in range 0..100.
        // When all fields are matched strictly, the score is 100
        // When all fields are matched loosely, the score is 50
        // Other cases are intermediate, respectively.

        $score = (
            $strictMatchesCount/$searchProfile->searchProfileValues->count()
            + $looseMatchesCount/(2 * $searchProfile->searchProfileValues->count())
        ) * 100.0;

        return [$score, $strictMatchesCount, $looseMatchesCount];
    }

    public function getMatchedSearchProfiles() : array
    {
        $this->load('propertyFieldValues');

        $s = SearchProfile::query()
            ->whereRaw('search_profiles.id in (select sp.id
                            from search_profiles sp
                            where exists(
                                    select *
                                    from search_profile_values spv inner join property_fields pf on spv.property_field_id = pf.id
                                    where sp.property_type = ?
                                          and spv.search_profile_id = sp.id
                                          and spv.property_field_id in (select pfv.property_field_id
                                                                        from property_field_values pfv
                                                                        where pfv.property_id = ?)
                                ))', [$this->property_type, $this->id])
            ->with('searchProfileValues.propertyField');

        $searchProfiles = $s->get();

        $result = [];

        foreach($searchProfiles as $searchProfile)
        {
            list($score, $strictMatchesCount, $looseMatchesCount) = $this->calculateMatch($searchProfile);
            if($strictMatchesCount > 0 || $looseMatchesCount > 0)
            {
                $result[] = [
                    'searchProfileId' => $searchProfile->id,
                    'score' => $score,
                    'strictMatchesCount' => $strictMatchesCount,
                    'looseMatchesCount' => $looseMatchesCount,
                ];
            }
        }

        return collect($result)
            ->sortByDesc('score', SORT_NUMERIC)
            ->values()
            ->all();
    }
}
