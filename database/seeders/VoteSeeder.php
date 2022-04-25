<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Vote;
use Faker\Generator;
use Illuminate\Database\Seeder;
use App\Models\Post;

final class VoteSeeder extends Seeder
{
    public function __construct(
        private readonly Generator $faker,
    ) {
    }

    public function run(): void
    {
        $this->addVotes(PostSeeder::COUNT, Post::class);
        // $this->addVotes(CommentSeeder::COUNT, Comment::class);
    }

    private function addVotes(int $count, string $class): void
    {
        $now = now();
        $items = [];
        foreach (range(1, $count) as $postId) {
            $this->faker->unique(true);

            foreach (range(1, $this->faker->numberBetween(1, UserSeeder::COUNT)) as $index) {
                $userId = $this->faker->unique()->numberBetween(1, UserSeeder::COUNT);

                $items[] = [
                    'user_id' => $userId,
                    'direction' => $this->faker->boolean,
                    'voteable_id' => $postId,
                    'voteable_type' => $class,
                    'created_at' => $now,
                ];
            }
        }

        Vote::insert($items);
    }
}
