<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'content' => $this->faker->text(),
            'user_id' => $this->faker->numberBetween(1, 20),
            'post_id' => $this->faker->numberBetween(1, 100),
        ];
    }
}
