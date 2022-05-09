<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\User;

/**
 * @internal
 */
final class DeleteTest extends UserTestCase
{
    /** @test */
    public function it_deletes_user(): void
    {
        $this->auth('users.delete');

        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->deleteJson(route('api.users.destroy', $user));

        $response->assertNoContent();

        $this->assertModelMissing($user);
    }

    /** @test */
    public function it_forbids_delete_user_without_permissions(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->deleteJson(route('api.users.destroy', $user));

        $response->assertForbidden();

        $this->assertModelExists($user);
    }

    /** @test */
    public function it_returns_not_found_when_wrong_user_deletes(): void
    {
        $response = $this->deleteJson(route('api.users.destroy', -1));

        $response->assertNotFound();
    }
}
