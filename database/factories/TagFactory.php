<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

final class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        $slug = $this->faker->unique()->word;
        $createdAt = $this->faker->dateTimeBetween('-1 month');

        return [
            'name' => $slug,
            'slug' => $slug,
            'order' => $this->faker->numberBetween(-100, 100),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
