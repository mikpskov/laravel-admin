<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Post;
use Faker\Generator;
use Illuminate\Database\Seeder;

final class PostSeeder extends Seeder
{
    public const COUNT = 200;

    public function __construct(
        private readonly Generator $faker,
    ) {
    }

    public function run(): void
    {
        foreach (range(1, self::COUNT) as $index)
        {
            $createdAt = $this->faker->dateTimeBetween('-1 month');

            $items[] = [
                'author_id' => random_int(1, UserSeeder::COUNT),
                'title' => rtrim($this->faker->sentence, '.'),
                'body' => $this->faker->text(2000),
                'published_at' => $this->faker->randomElement([now(), null]),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        Post::insert($items);
    }
}
