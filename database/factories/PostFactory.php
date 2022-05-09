<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

final class PostFactory extends Factory
{
    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-1 month');

        return [
            'user_id' => User::factory(),
            'title' => rtrim($this->faker->sentence, '.'),
            'body' => $this->faker->text(2000),
            'published_at' => $this->faker->randomElement([now(), null]),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }

    public function published(): self
    {
        return $this->state(fn(array $attributes) => [
            'published_at' => now(),
        ]);
    }

    public function unpublished(): self
    {
        return $this->state(fn(array $attributes) => [
            'published_at' => null,
        ]);
    }
}
