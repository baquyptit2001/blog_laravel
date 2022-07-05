<?php

namespace Database\Factories;

use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    #[ArrayShape(['title' => "string", 'content' => "string", 'category_id' => "int", 'user_id' => "int"])] public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph(random_int(5, 10)),
            'category_id' => $this->faker->numberBetween(1, 10),
            'user_id' => $this->faker->numberBetween(1, 20),
            'image' => "https://picsum.photos/500",
        ];
    }
}
