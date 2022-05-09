<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\User;
use App\Services\Response\Message;
use Illuminate\Testing\Fluent\AssertableJson;

/**
 * @internal
 */
final class ShowTest extends UserTestCase
{
    /** @test */
    public function it_shows_user(): void
    {
        $this->auth('users.view');

        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->getJson(route('api.users.show', $user));

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->where('data.id', $user->id)
                    ->where('data.name', $user->name)
                    ->where('data.email', $user->email)
            );
    }

    /** @test */
    public function it_forbids_view_user_without_permissions(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->getJson(route('api.users.index', $user));

        $response->assertForbidden();
    }

    /** @test */
    public function it_returns_not_found_when_wrong_user_shows(): void
    {
        $response = $this->getJson(route('api.users.show', -1));

        $response->assertNotFound();
    }
}
