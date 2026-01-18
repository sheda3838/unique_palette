<?php

namespace Database\Factories;

use App\Models\Artwork;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artwork>
 */
class ArtworkFactory extends Factory
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
            'title' => 'Artwork ' . fake()->unique()->numberBetween(1, 1000),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 5000, 500000),
            'image_path' => 'assets/artworks/a1.jpg', // Default, will override in seeder
            'status' => 'pending',
        ];
    }
}
