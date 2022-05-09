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
final class UpdateTest extends TestCase
{
    /** @test */
    public function it_updates_foreign_post(): void
    {
        $this->auth('posts.update_any');

        /** @var Post $post */
        $post = Post::factory()->create();

        $data = Post::factory()
            ->make()
            ->only(['title', 'body']);

        $response = $this->putJson(route('api.posts.update', $post), $data);

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->where('data.id', $post->id)
                    ->where('data.title', $data['title'])
                    ->where('data.body', $data['body'])
            );
    }

    /** @test */
    public function it_updates_own_post(): void
    {
        $this->auth('posts.update_own');

        /** @var Post $post */
        $post = Post::factory()
            ->for($this->user, 'user')
            ->create();

        $data = Post::factory()
            ->make()
            ->only(['title', 'body']);

        $response = $this->putJson(route('api.posts.update', $post), $data);

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->where('data.id', $post->id)
                    ->where('data.title', $data['title'])
                    ->where('data.body', $data['body'])
            );
    }

    /** @test */
    public function it_partial_updates_post(): void
    {
        $this->auth('posts.update_any');

        /** @var Post $post */
        $post = Post::factory()->create();

        $data = Post::factory()
            ->make()
            ->only('title');

        $response = $this->patchJson(route('api.posts.update', $post), $data);

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->where('data.id', $post->id)
                    ->where('data.title', $data['title'])
                    ->where('data.body', $post->body)
            );
    }

    /** @test */
    public function it_forbids_update_foreign_post_without_permission(): void
    {
        $this->auth('posts.update_own');

        /** @var Post $post */
        $post = Post::factory()->create();

        $response = $this->putJson(route('api.posts.update', $post), ['title' => 'Test title']);

        $response->assertForbidden();
    }

    /** @test */
    public function it_forbids_update_post_without_permission(): void
    {
        $this->auth();

        /** @var Post $post */
        $post = Post::factory()
            ->for($this->user, 'user')
            ->create();

        $response = $this->putJson(route('api.posts.update', $post), ['title' => 'Test title']);

        $response->assertForbidden();
    }

    /** @test */
    public function it_forbids_store_post_for_unauthenticated_user(): void
    {
        /** @var Post $post */
        $post = Post::factory()
            ->for($this->user, 'user')
            ->create();

        $response = $this->putJson(route('api.posts.update', $post), ['title' => 'Test title']);

        $response->assertUnauthorized();
    }

    /** @test */
    public function it_returns_not_found_when_wrong_post_updates(): void
    {
        $this->auth('posts.update_any');

        $response = $this->putJson(route('api.posts.show', -1), ['title' => 'Test title']);

        $response->assertNotFound();
    }
}
