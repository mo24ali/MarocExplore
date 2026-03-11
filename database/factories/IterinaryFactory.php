<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Iterinary>
 */
class IterinaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'category' => $this->faker->randomElement(['plage', 'montagne', 'rivière', 'monument']),
            'duration' => $this->faker->randomElement(['1 jour', '3 jours', '7 jours']),
            'image' => $this->faker->imageUrl(640, 480, 'travel'),
            'user_id' => User::factory(),
        ];
    }
}
