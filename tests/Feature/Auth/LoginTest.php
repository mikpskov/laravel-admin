<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Services\Response\Message;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\TestCase;

/**
 * @internal
 */
final class LoginTest extends TestCase
{
    /** @test */
    public function it_logins_with_right_credentials(): void
    {
        $response = $this->postJson(route('api.auth.store'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->has('data.access_token')
                    ->has('data.token_type')
            );
    }

    /** @test */
    public function it_fails_login_without_email(): void
    {
        $response = $this->postJson(route('api.auth.store'), [
            'password' => 'password',
        ]);

        $response->assertUnprocessable();
    }

    /** @test */
    public function it_fails_login_without_password(): void
    {
        $response = $this->postJson(route('api.auth.store'), [
            'email' => $this->user->email,
        ]);

        $response->assertUnprocessable();
    }

    /** @test */
    public function it_fails_login_with_wrong_credentials(): void
    {
        $response = $this->postJson(route('api.auth.store'), [
            'email' => $this->user->email,
            'password' => 'wrong_password',
        ]);

        $response->assertUnauthorized();
    }
}
