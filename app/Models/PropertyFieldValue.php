<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PropertyFieldValue
 *
 * @property int $id
 * @property int $property_field_id
 * @property int $property_id
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\PropertyFieldValueFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyFieldValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyFieldValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyFieldValue query()
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyFieldValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyFieldValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyFieldValue wherePropertyFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyFieldValue wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyFieldValue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropertyFieldValue whereValue($value)
 * @mixin \Eloquent
 */
class PropertyFieldValue extends Model
{
    use HasFactory;

    public function propertyField()
    {
        return $this->belongsTo(PropertyField::class, 'property_field_id', 'id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }
}
