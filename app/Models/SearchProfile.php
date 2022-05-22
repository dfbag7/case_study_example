<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SearchProfile
 *
 * @property int $id
 * @property string $name
 * @property string $property_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SearchProfileValue[] $searchProfileValues
 * @property-read int|null $search_profile_values_count
 * @method static \Database\Factories\SearchProfileFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfile wherePropertyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchProfile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SearchProfile extends Model
{
    use HasFactory;

    public function searchProfileValues()
    {
        return $this->hasMany(SearchProfileValue::class, 'search_profile_id', 'id');
    }
}
