<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SearchProfileValue
 *
 * @property int $id
 * @property int $search_profile_id
 * @property int $property_field_id
 * @property string $kind
 * @property string|null $direct_value
 * @property string|null $min_range_value
 * @property string|null $max_range_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\SearchProfileValueFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfileValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfileValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfileValue query()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfileValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfileValue whereDirectValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfileValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfileValue whereKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfileValue whereMaxRangeValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfileValue whereMinRangeValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfileValue wherePropertyFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfileValue whereSearchProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfileValue whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SearchProfileValue extends Model
{
    use HasFactory;

    const DIRECT = 'direct';
    const RANGE = 'range';

    public function searchProfile()
    {
        return $this->belongsTo(SearchProfile::class, 'search_profile_id', 'id');
    }

    public function propertyField()
    {
        return $this->belongsTo(PropertyField::class, 'property_field_id', 'id');
    }
}
