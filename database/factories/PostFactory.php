<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

final class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'author_id' => random_int(1, 50),  // todo: Post::factory(),
            'title' => rtrim($this->faker->sentence, '.'),
            'body' => $this->faker->paragraph,
            'published_at' => $this->faker->randomElement([now(), null]),
        ];
    }
}
