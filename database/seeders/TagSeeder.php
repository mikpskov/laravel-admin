<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class TagSeeder extends Seeder
{
    public const COUNT = 30;
    public const MAX_TAGS = 7;

    public function __construct(
        private readonly Generator $faker,
    ) {
    }

    public function run(): void
    {
        $this->addTags();
        $this->addTaggables(PostSeeder::COUNT, Post::class);
    }

    private function addTags(): void
    {
        foreach (range(1, self::COUNT) as $index) {
            $slug = $this->faker->unique()->word;
            $createdAt = $this->faker->dateTimeBetween('-1 month');

            $items[] = [
                'name' => $slug,
                'slug' => $slug,
                'order' => $this->faker->numberBetween(-100, 100),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        Tag::insert($items);
    }

    private function addTaggables(int $count, string $taggableType, int $maxTags = self::MAX_TAGS): void
    {
        $items = [];
        foreach (range(1, $count) as $taggableId) {
            $this->faker->unique(true);

            foreach (range(1, $this->faker->numberBetween(1, $maxTags)) as $index) {
                $tagId = $this->faker->unique()->numberBetween(1, self::COUNT);

                $items[] = [
                    'tag_id' => $tagId,
                    'taggable_id' => $taggableId,
                    'taggable_type' => $taggableType,
                ];
            }
        }

        DB::table('taggables')->insert($items);
    }
}
