<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PropertyField
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PropertyFieldValue[] $propertyFieldValues
 * @property-read int|null $property_field_values_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SearchProfileValue[] $searchProfileValues
 * @property-read int|null $search_profile_values_count
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyField query()
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyField whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PropertyField extends Model
{
    use HasFactory;

    public function propertyFieldValues()
    {
        return $this->hasMany(PropertyFieldValue::class, 'property_field_id', 'id');
    }

    public function searchProfileValues()
    {
        return $this->hasMany(SearchProfileValue::class, 'search_profile_id', 'id');
    }
}
