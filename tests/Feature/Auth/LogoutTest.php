<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Laravel\Sanctum\Sanctum;
use Tests\Feature\TestCase;

/**
 * @internal
 */
final class LogoutTest extends TestCase
{
    /** @test */
    public function it_logouts_by_authenticated_user(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson(route('api.auth.destroy'));

        $response->assertNoContent();
    }

    /** @test */
    public function it_fails_logout_by_guest(): void
    {
        $response = $this->deleteJson(route('api.auth.destroy'));

        $response->assertUnauthorized();
    }
}
