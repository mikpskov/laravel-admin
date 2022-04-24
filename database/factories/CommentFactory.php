<?php

declare(strict_types=1);

namespace Database\Factories;

use Database\Seeders\PostSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;

final class CommentFactory extends Factory
{
    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-1 month');

        return [
            'user_id' => random_int(1, UserSeeder::COUNT),  // todo: User::factory(),
            'post_id' => random_int(1, PostSeeder::COUNT),  // todo: Post::factory(),
            'body' => $this->faker->text,
            'approved_at' => $this->faker->randomElement([now(), null]),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
