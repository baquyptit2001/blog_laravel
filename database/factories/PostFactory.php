<?php

namespace Database\Factories;

use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends Factory
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    #[ArrayShape(['title' => "string", 'content' => "string", 'category_id' => "int", 'user_id' => "int", "image" => "string"])] public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph(random_int(5, 10)),
            'category_id' => $this->faker->numberBetween(1, 10),
            'user_id' => $this->faker->numberBetween(1, 20),
            'image' => "https://source.unsplash.com/random/1000x1000?sig=" . $this->faker->numberBetween(1, 1000),
        ];
    }
}
