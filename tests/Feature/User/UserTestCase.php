<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\Feature\TestCase;

abstract class UserTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // reset cached roles and permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Sanctum::actingAs(
            user: $this->user,
            guard: 'web',
        );
    }

    protected function givePermission(string $permission): void
    {
        $permission = Permission::create(['name' => $permission]);
        $this->user->givePermissionTo($permission);
    }
}
