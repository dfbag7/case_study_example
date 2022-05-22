<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyField>
 */
class PropertyFieldFactory extends Factory
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
            'name' => $this->faker->domainWord,
            'created_at' => $createdAt,
            'updated_at' => $this->faker->dateTimeBetween(startDate: $createdAt),
        ];
    }
}
