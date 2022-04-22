<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

final class PostSeeder extends Seeder
{
    public const COUNT = 200;

    public function run(): void
    {
        Post::factory()
            ->count(self::COUNT)
            ->create();
    }
}
