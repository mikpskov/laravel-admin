<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Notifications\NewCommentNotification;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Notifications\DatabaseNotification;

final class NotificationSeeder extends Seeder
{
    public const COUNT = 20;

    public function __construct(
        private readonly Generator $faker,
    ) {
    }

    public function run(): void
    {
        $items = [];
        foreach (range(1, UserSeeder::COUNT) as $userId) {
            foreach (range(1, $this->faker->numberBetween(1, self::COUNT)) as $index) {
                $createdAt = $this->faker->dateTimeBetween('-1 month');

                $items[] = [
                    'id' => $this->faker->uuid,
                    'type' => $this->faker->randomElement([
                        NewCommentNotification::class,
                    ]),
                    'notifiable_type' => User::class,
                    'notifiable_id' => $userId,
                    'data' => json_encode([
                        'comment_id' => $this->faker->numberBetween(1, CommentSeeder::COUNT),
                        'post_id' => $this->faker->numberBetween(1, PostSeeder::COUNT),
                        'post_title' => rtrim($this->faker->sentence, '.'),
                        'user_id' => $this->faker->numberBetween(1, UserSeeder::COUNT),
                        'user_name' => $this->faker->name(),
                    ]),
                    'read_at' => $this->faker->randomElement([$this->faker->dateTimeBetween($createdAt), null]),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];
            }
        }

        DatabaseNotification::insert($items);
    }
}
