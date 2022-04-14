<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

final class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'users.viewAny']);
        Permission::create(['name' => 'users.view']);
        Permission::create(['name' => 'users.create']);
        Permission::create(['name' => 'users.update']);
        Permission::create(['name' => 'users.delete']);

        Permission::create(['name' => 'posts.viewAny']);
        Permission::create(['name' => 'posts.view']);
        Permission::create(['name' => 'posts.create']);
        Permission::create(['name' => 'posts.update']);
        Permission::create(['name' => 'posts.delete']);

        Role::create(['name' => 'super_admin']);

        Role::create(['name' => 'admin'])
            ->givePermissionTo([
                'users.viewAny',
                'users.view',
                'users.create',
                'users.update',
                'users.delete',
            ]);

        Role::create(['name' => 'editor'])
            ->givePermissionTo([
                'posts.viewAny',
                'posts.view',
                'posts.create',
                'posts.update',
                'posts.delete',
            ]);
    }
}
