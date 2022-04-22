<?php

declare(strict_types=1);

namespace Database\Seeders;

use Faker\Generator;
use Illuminate\Database\Seeder;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

final class LikeSeeder extends Seeder
{
    public function __construct(
        private readonly Generator $faker,
    ) {
    }

    public function run(): void
    {
        $now = now();
        $data = [];
        for ($postId = 1; $postId <= PostSeeder::COUNT; ++$postId) {
            $this->faker->unique(true);

            for ($i = 1; $i <= $this->faker->numberBetween(1, UserSeeder::COUNT); ++$i) {
                $userId = $this->faker->unique()->numberBetween(1, UserSeeder::COUNT);

                $data[] = [
                    'user_id' => $userId,
                    'likeable_id' => $postId,
                    'likeable_type' => Post::class,
                    'created_at' => $now,
                ];
            }
        }

        DB::table('likes')->insert($data);
    }
}
