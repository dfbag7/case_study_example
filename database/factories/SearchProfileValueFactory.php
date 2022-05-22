<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SearchProfileValue>
 */
class SearchProfileValueFactory extends Factory
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
            'search_profile_id' => null,
            'property_field_id' => null,
            'kind' => null,
            'direct_value' => null,
            'min_range_value' => null,
            'max_range_value' => null,
            'created_at' => $createdAt,
            'updated_at' => $this->faker->dateTimeBetween(startDate: $createdAt),
        ];
    }
}
