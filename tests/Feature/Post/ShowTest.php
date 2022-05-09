<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Services\Response\Message;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\TestCase;

/**
 * @internal
 */
final class ShowTest extends TestCase
{
    /** @test */
    public function it_shows_foreign_post(): void
    {
        $this->auth('posts.view_any');

        /** @var Post $post */
        $post = Post::factory()->create();

        $response = $this->getJson(route('api.posts.show', $post));

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->where('data.id', $post->id)
                    ->where('data.title', $post->title)
            );
    }

    /** @test */
    public function it_shows_own_post(): void
    {
        $this->auth('posts.view_own');

        /** @var Post $post */
        $post = Post::factory()
            ->for($this->user, 'user')
            ->create();

        $response = $this->getJson(route('api.posts.show', $post));

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->where('data.id', $post->id)
                    ->where('data.title', $post->title)
            );
    }

    /** @test */
    public function it_forbids_view_foreign_post_without_permission(): void
    {
        $this->auth('posts.view_own');

        /** @var Post $post */
        $post = Post::factory()->create();

        $response = $this->getJson(route('api.posts.show', $post));

        $response->assertForbidden();
    }

    /** @test */
    public function it_forbids_view_post_without_permission(): void
    {
        $this->auth();

        /** @var Post $post */
        $post = Post::factory()
            ->for($this->user, 'user')
            ->create();

        $response = $this->getJson(route('api.posts.index', $post));

        $response->assertForbidden();
    }

    /** @test */
    public function it_returns_not_found_when_wrong_post_shows(): void
    {
        $this->auth('posts.view_any');

        $response = $this->getJson(route('api.posts.show', -1));

        $response->assertNotFound();
    }
}
