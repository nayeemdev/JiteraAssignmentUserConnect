<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'street' => fake()->streetAddress(),
            'suite' => fake()->streetSuffix(),
            'city' => fake()->city(),
            'zipcode' => fake()->postcode(),
            'lat' => fake()->latitude(),
            'lng' => fake()->longitude(),
        ];
    }
}
