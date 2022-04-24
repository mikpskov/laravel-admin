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
        $password = Hash::make('Test123!');

        User::factory()
            ->createOne([
                'email' => 'admin@test.com',
                'password' => $password,
            ])
            ->assignRole('super_admin');

        $items = [];
        foreach (range(1, self::COUNT - 1) as $index)
        {
            $items[] = [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => $password,
                'remember_token' => Str::random(10),
            ];
        }

        User::insert($items);
    }
}
