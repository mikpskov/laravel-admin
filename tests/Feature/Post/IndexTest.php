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
final class IndexTest extends TestCase
{
    /** @test */
    public function it_shows_list_of_any_posts(): void
    {
        $this->auth('posts.view_any');

        Post::factory($count = 10)
            ->create();

        $response = $this->getJson(
            route('api.posts.index', [
                'include' => 'user',
            ]),
        );

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->has('data', $count)
                    ->has('data.0.id')
                    ->has('data.0.title')
                    ->has('data.0.body')
                    ->has('data.0.published')
                    ->has('data.0.created_at')
                    ->has('data.0.updated_at')
                    ->has('data.0.user.id')
                    ->has('data.0.user.name')
            );
    }

    /** @test */
    public function it_shows_list_of_only_own_posts(): void
    {
        $this->auth('posts.view_own');

        Post::factory(7)
            ->create();

        Post::factory($count = 3)
            ->for($this->user, 'user')
            ->create();

        $response = $this->getJson(route('api.posts.index'));

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->has('data', $count)
            );
    }

    /** @test */
    public function it_filters_list_of_posts(): void
    {
        $this->auth('posts.view_any');

        Post::factory(7)
            ->create();

        Post::factory($count = 3)
            ->for($this->user, 'user')
            ->create();

        $response = $this->getJson(
            route('api.posts.index', [
                'filter[user_id]' => $this->user->id,
            ])
        );

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->has('data', $count)
            );
    }

    /** @test */
    public function it_sorts_list_of_posts(): void
    {
        $this->auth('posts.view_any');

        $count = collect([
            ['id' => 100, 'title' => 'aaa'],
            ['id' => 200, 'title' => 'zzz'],
            ['id' => 300, 'title' => 'aaa'],
        ])
            ->map(fn($postData) => Post::factory()->create($postData))
            ->count();

        $response = $this->getJson(
            route('api.posts.index', [
                'sort' => 'title,-id',
            ])
        );

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->has('data', $count)
                    ->where('data.0.id', 300)
                    ->where('data.1.id', 100)
                    ->where('data.2.id', 200)
            );
    }

    /** @test */
    public function it_forbids_view_list_of_posts_without_permission(): void
    {
        $this->auth();

        $response = $this->getJson(route('api.posts.index'));

        $response->assertForbidden();
    }

    /** @test */
    public function it_forbids_view_list_of_posts_for_unauthenticated_user(): void
    {
        $response = $this->getJson(route('api.posts.index'));

        $response->assertUnauthorized();
    }

    // todo: it_shows_paginated_list_of_posts()
}
