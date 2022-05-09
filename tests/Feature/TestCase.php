<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var User $user */
        $user = User::factory()->create();

        $this->user = $user;

        // reset cached roles and permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * @param string|array<string> $permissions
     */
    public function auth(string|array $permissions = [], ?UserContract $user = null, $guard = null): static
    {
        Sanctum::actingAs(
            user: $user ?? $this->user,
            guard: 'web',
        );

        $this->givePermissions(
            is_array($permissions) ? $permissions : [$permissions],
        );

        return $this;
    }

    protected function givePermissions(string|array $names = [], ?string $guardName = null): static
    {
        $this->user->givePermissionTo(collect($names)
            ->map(fn(string $name) => Permission::findOrCreate($name, $guardName)),
        );

        return $this;
    }
}
