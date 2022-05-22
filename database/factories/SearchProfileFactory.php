<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SearchProfile>
 */
class SearchProfileFactory extends Factory
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
            'name' => $this->faker->sentence,
            // for testing purposes, the first element of the array must be the same as in PropertyFactory class
            'property_type' => $this->faker->randomElement(['d44d0090-a2b5-47f7-80bb-d6e6f85fca90', '6a08493f-d9b8-11ec-ad7d-0242ac160005', '72fba165-d9b8-11ec-ad7d-0242ac160005']),
            'created_at' => $createdAt,
            'updated_at' => $this->faker->dateTimeBetween(startDate: $createdAt),
        ];
    }
}
