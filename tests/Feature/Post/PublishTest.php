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
final class PublishTest extends TestCase
{
    /** @test */
    public function it_publishes_foreign_post(): void
    {
        $this->auth('posts.publish_any');

        /** @var Post $post */
        $post = Post::factory()
            ->unpublished()
            ->create();

        $response = $this->patchJson(route('api.posts.publish', $post));

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->where('data.published', true)
            );
    }

    /** @test */
    public function it_unpublishes_own_post(): void
    {
        $this->auth('posts.publish_own');

        /** @var Post $post */
        $post = Post::factory()
            ->for($this->user, 'user')
            ->published()
            ->create();

        $response = $this->deleteJson(route('api.posts.unpublish', $post));

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->where('data.published', false)
            );
    }

    /** @test */
    public function it_forbids_publish_foreign_post_without_permission(): void
    {
        $this->auth('posts.publish_own');

        /** @var Post $post */
        $post = Post::factory()
            ->unpublished()
            ->create();

        $response = $this->patchJson(route('api.posts.publish', $post));

        $response->assertForbidden();
    }

    /** @test */
    public function it_forbids_unpublish_post_without_permission(): void
    {
        $this->auth();

        /** @var Post $post */
        $post = Post::factory()
            ->for($this->user, 'user')
            ->published()
            ->create();

        $response = $this->deleteJson(route('api.posts.unpublish', $post));

        $response->assertForbidden();
    }

    /** @test */
    public function it_forbids_publish_post_for_unauthenticated_user(): void
    {
        /** @var Post $post */
        $post = Post::factory()
            ->unpublished()
            ->create();

        $response = $this->patchJson(route('api.posts.publish', $post));

        $response->assertUnauthorized();
    }

    /** @test */
    public function it_returns_not_found_when_wrong_post_unpublishes(): void
    {
        $this->auth('posts.publish_any');

        $response = $this->deleteJson(route('api.posts.unpublish', -1));

        $response->assertNotFound();
    }
}
