<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class UserSeeder extends Seeder
{
    public const COUNT = 50;

    public function run(): void
    {
        User::factory()
            ->createOne([
                'email' => 'admin@test.com',
                'password' => Hash::make('Test123!'),
            ])
            ->assignRole('super_admin');

        User::factory()
            ->count(self::COUNT - 1)
            ->create();
    }
}
