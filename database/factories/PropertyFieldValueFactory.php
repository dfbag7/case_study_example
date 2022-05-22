<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyFieldValue>
 */
class PropertyFieldValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $createdAt = $this->faker->dateTimeThisDecade;

        return [
            'property_field_id' => null,
            'property_id' => null,
            'value' => null,
            'created_at' => $createdAt,
            'updated_at' => $this->faker->dateTimeBetween(startDate: $createdAt),
        ];
    }
}
