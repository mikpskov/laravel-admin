<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Like;
use Faker\Generator;
use Illuminate\Database\Seeder;
use App\Models\Post;

final class LikeSeeder extends Seeder
{
    public function __construct(
        private readonly Generator $faker,
    ) {
    }

    public function run(): void
    {
        $this->addLikes(PostSeeder::COUNT, Post::class);
        $this->addLikes(CommentSeeder::COUNT, Comment::class);
    }

    private function addLikes(int $count, string $likeableType): void
    {
        $now = now();
        $items = [];
        foreach (range(1, $count) as $likeableId) {
            $this->faker->unique(true);

            foreach (range(1, $this->faker->numberBetween(1, UserSeeder::COUNT)) as $index) {
                $userId = $this->faker->unique()->numberBetween(1, UserSeeder::COUNT);

                $items[] = [
                    'user_id' => $userId,
                    'likeable_id' => $likeableId,
                    'likeable_type' => $likeableType,
                    'created_at' => $now,
                ];
            }
        }

        Like::insert($items);
    }
}
