<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Http\Resources\UserResource;
use App\Services\Response\Message;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\TestCase;

/**
 * @internal
 */
final class MeTest extends TestCase
{
    /** @test */
    public function it_shows_user_profile(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson(route('api.auth.show'));

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->where('data.id', $this->user->id)
                    ->where('data.name', $this->user->name)
                    ->where('data.email', $this->user->email)
            );
    }

    /** @test */
    public function it_fails_show_profile_for_guest(): void
    {
        $response = $this->getJson(route('api.auth.show'));

        $response->assertUnauthorized();
    }
}
