<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class UserSeeder extends Seeder
{
    public const COUNT = 50;

    public function __construct(
        private readonly Generator $faker,
    ) {
    }

    public function run(): void
    {
        $passwordHash = Hash::make($password = 'Test123!');

        User::factory()
            ->createOne([
                'email' => 'admin@test.com',
                'password' => $password,
            ])
            ->assignRole('super_admin');

        $items = [];
        foreach (range(1, self::COUNT - 1) as $index)
        {
            $createdAt = $this->faker->dateTimeBetween('-1 month');

            $items[] = [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => $passwordHash,
                'remember_token' => Str::random(10),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        User::insert($items);
    }
}
