<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\User;
use App\Services\Response\Message;
use Illuminate\Testing\Fluent\AssertableJson;

/**
 * @internal
 */
final class StoreTest extends UserTestCase
{
    /** @test */
    public function it_stores_user(): void
    {
        $this->auth('users.create');

        /** @var User $user */
        $user = User::factory()->make();

        $response = $this->postJson(route('api.users.store'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertCreated()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->has('data.id')
                    ->where('data.name', $user->name)
                    ->where('data.email', $user->email)
            );

        $user->id = (int)$response->json('data.id');

        $this->assertModelExists($user);
    }

    /** @test */
    public function it_forbids_store_user_without_permissions(): void
    {
        /** @var User $user */
        $user = User::factory()->make();

        $response = $this->postJson(route('api.users.store'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertForbidden();
    }

    /** @test */
    public function it_fails_store_user_without_required_fields(): void
    {
        $this->auth('users.create');

        $response = $this->postJson(route('api.users.store'), []);

        $response
            ->assertUnprocessable()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->has('message')
                    ->has('errors.email.0')
                    ->has('errors.name.0')
                    ->has('errors.password.0')
            );
    }

    /** @test */
    public function it_fails_store_user_with_wrong_email(): void
    {
        $this->auth('users.create');

        $response = $this->postJson(route('api.users.store'), [
            'name' => 'John Doe',
            'email' => 'wrong_email',
            'password' => 'password',
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
    public function it_fails_store_user_with_non_unique_email(): void
    {
        $this->auth('users.create');

        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->postJson(route('api.users.store'), [
            'name' => 'John Doe',
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->has('message')
                    ->has('errors.email.0')
            );
    }
}
