<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vendor_id' => 1, // Assuming vendor with ID 1 exists
            'name' => $this->faker->company(),
            'slug' => $this->faker->slug(),
            'logo' => null,
            'description' => $this->faker->paragraph(),
            'status' => 'active',
        ];
    }
}
