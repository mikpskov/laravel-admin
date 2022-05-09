<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\User;
use App\Services\Response\Message;
use Illuminate\Testing\Fluent\AssertableJson;

/**
 * @internal
 */
final class IndexTest extends UserTestCase
{
    /** @test */
    public function it_shows_list_of_users(): void
    {
        $this->auth('users.view_any');

        User::factory($count = 9)->create();

        $response = $this->getJson(route('api.users.index'));

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->has('data', $count + 1)
                    ->has('data.0.id')
                    ->has('data.0.name')
                    ->has('data.0.email')
            );
    }

    // todo: it_shows_paginated_list_of_users()

    /** @test */
    public function it_forbids_view_list_of_users_without_permissions(): void
    {
        $response = $this->getJson(route('api.users.index'));

        $response->assertForbidden();
    }
}
