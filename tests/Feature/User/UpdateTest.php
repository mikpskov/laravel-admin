<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\User;
use App\Services\Response\Message;
use Illuminate\Testing\Fluent\AssertableJson;

/**
 * @internal
 */
final class UpdateTest extends UserTestCase
{
    /** @test */
    public function it_updates_user(): void
    {
        $this->givePermission('users.update');

        /** @var User $user */
        $user = User::factory()->create();

        $data = User::factory()
            ->make()
            ->only(['name', 'email']);

        $response = $this->putJson(route('api.users.update', $user), $data);

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->where('data.id', $user->id)
                    ->where('data.name', $data['name'])
                    ->where('data.email', $data['email'])
            );
    }

    /** @test */
    public function it_partial_updates_user(): void
    {
        $this->givePermission('users.update');

        /** @var User $user */
        $user = User::factory()->create();

        $data = User::factory()->make()->only(['email']);

        $response = $this->patchJson(route('api.users.update', $user), $data);

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->where('data.id', $user->id)
                    ->where('data.name', $user->name)
                    ->where('data.email', $data['email'])
            );
    }

    /** @test */
    public function it_forbids_update_user_without_permissions(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $data = User::factory()->make()->only(['email']);

        $response = $this->patchJson(route('api.users.update', $user), $data);

        $response->assertForbidden();
    }

    /** @test */
    public function it_fails_update_user_with_non_unique_email(): void
    {
        $this->givePermission('users.update');

        /** @var User $user */
        $user1 = User::factory()->create();

        /** @var User $user */
        $user2 = User::factory()->create();

        $response = $this->patchJson(route('api.users.update', $user1), [
            'email' => $user2->email,
        ]);

        $response
            ->assertUnprocessable()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->has('message')
                    ->has('errors.email.0')
            );
    }

    /** @test */
    public function it_returns_not_found_when_wrong_user_updates(): void
    {
        $data = User::factory()->make()->only(['email']);

        $response = $this->putJson(route('api.users.show', -1), $data);

        $response->assertNotFound();
    }
}
