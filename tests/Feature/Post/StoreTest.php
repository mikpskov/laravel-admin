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
final class StoreTest extends TestCase
{
    /** @test */
    public function it_stores_post(): void
    {
        $this->auth('posts.create');

        /** @var Post $post */
        $post = Post::factory()->make();

        $response = $this->postJson(
            route('api.posts.store'),
            $post->only(['title', 'body']),
        );

        $response
            ->assertCreated()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->has('data.id')
                    ->where('data.title', $post->title)
                    ->where('data.body', $post->body)
            );

        // todo: check user_id

        $post->id = (int)$response->json('data.id');

        $this->assertModelExists($post);
    }

    /** @test */
    public function it_forbids_store_post_without_permission(): void
    {
        $this->auth();

        /** @var Post $post */
        $post = Post::factory()->make();

        $response = $this->postJson(
            route('api.posts.store'),
            $post->only(['title', 'body']),
        );

        $response->assertForbidden();
    }

    /** @test */
    public function it_forbids_store_post_for_unauthenticated_user(): void
    {
        /** @var Post $post */
        $post = Post::factory()->make();

        $response = $this->postJson(
            route('api.posts.store'),
            $post->only(['title', 'body']),
        );

        $response->assertUnauthorized();
    }

    /** @test */
    public function it_fails_store_post_without_required_fields(): void
    {
        $this->auth('posts.create');

        $response = $this->postJson(route('api.posts.store'), []);

        $response
            ->assertUnprocessable()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->has('message')
                    ->has('errors.title.0')
                    ->has('errors.body.0')
            );
    }
}
