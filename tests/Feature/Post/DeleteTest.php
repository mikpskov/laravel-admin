<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Models\Post;
use Tests\Feature\TestCase;

/**
 * @internal
 */
final class DeleteTest extends TestCase
{
    /** @test */
    public function it_deletes_foreign_post(): void
    {
        $this->auth('posts.delete_any');

        /** @var Post $post */
        $post = Post::factory()->create();

        $response = $this->deleteJson(route('api.posts.destroy', $post));

        $response->assertNoContent();

        $this->assertModelMissing($post);
    }

    /** @test */
    public function it_deletes_own_post(): void
    {
        $this->auth('posts.delete_own');

        /** @var Post $post */
        $post = Post::factory()
            ->for($this->user, 'user')
            ->create();

        $response = $this->deleteJson(route('api.posts.destroy', $post));

        $response->assertNoContent();

        $this->assertModelMissing($post);
    }

    /** @test */
    public function it_forbids_delete_post_without_permission(): void
    {
        $this->auth('posts.delete_own');

        /** @var Post $post */
        $post = Post::factory()->create();

        $response = $this->deleteJson(route('api.posts.destroy', $post));

        $response->assertForbidden();

        $this->assertModelExists($post);
    }

    /** @test */
    public function it_forbids_delete_post_for_unauthenticated_user(): void
    {
        /** @var Post $post */
        $post = Post::factory()->create();

        $response = $this->deleteJson(route('api.posts.destroy', $post));

        $response->assertUnauthorized();

        $this->assertModelExists($post);
    }

    /** @test */
    public function it_returns_not_found_when_wrong_post_deletes(): void
    {
        $this->auth('posts.delete_any');

        $response = $this->deleteJson(route('api.posts.destroy', -1));

        $response->assertNotFound();
    }
}
