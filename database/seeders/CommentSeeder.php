<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Comment;
use Faker\Generator;
use Illuminate\Database\Seeder;

final class CommentSeeder extends Seeder
{
    public const COUNT = 2000;

    public function __construct(
        private readonly Generator $faker,
    ) {
    }

    public function run(): void
    {
        $items = [];
        foreach (range(1, self::COUNT) as $index) {
            $createdAt = $this->faker->dateTimeBetween('-1 month');
            $approvedAt = $this->faker->randomElement([now(), null]);

            $items[] = [
                'author_id' => random_int(1, UserSeeder::COUNT),
                'post_id' => random_int(1, PostSeeder::COUNT),
                'body' => $this->faker->text,
                'approved_at' => $approvedAt,
                'approved_by' => $approvedAt ? random_int(1, UserSeeder::COUNT) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        Comment::insert($items);
    }
}
