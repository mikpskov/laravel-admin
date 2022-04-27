<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

final class PostFactory extends Factory
{
    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-1 month');

        return [
            'user_id' => random_int(1, 50),  // todo: Post::factory(),
            'title' => rtrim($this->faker->sentence, '.'),
            'body' => $this->faker->text(2000),
            'published_at' => $this->faker->randomElement([now(), null]),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
