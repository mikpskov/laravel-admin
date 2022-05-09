<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Services\Response\Message;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\TestCase;

/**
 * @internal
 */
final class TagsTest extends TestCase
{
    /** @test */
    public function it_filters_list_of_posts_by_tags(): void
    {
        $this->auth('posts.view_any');

        $posts = $this->createPostsWithTags([
            ['tag-1', 'tag 2'],
            ['tag 2', 'tag 3'],
            ['tag-3', 'tag-4'],
        ]);

        $response = $this->getJson(
            route('api.posts.index', [
                'include' => 'tags',
                'filter[tags]' => 'tag-3,tag-4',
            ])
        );

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->has('data', 2)
                    ->where('data.0.id', $posts[1]->id)
                    ->where('data.1.id', $posts[2]->id)
            );
    }

    /** @test */
    public function it_stores_post_with_tags(): void
    {
        $this->auth('posts.create');

        /** @var Post $post */
        $post = Post::factory()->make();

        $tagsString = ' ,, tag 1,tag-2 , , tag 3 ';

        $response = $this->postJson(
            route('api.posts.store'),
            array_merge($post->only(['title', 'body']), ['tags' => $tagsString]),
        );

        $response
            ->assertCreated()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->has('data.id')
                    ->has('data.tags', 3)
                    ->where('data.tags.0.slug', 'tag-1')
                    ->where('data.tags.1.slug', 'tag-2')
                    ->where('data.tags.2.slug', 'tag-3')
            );

        $post->id = (int)$response->json('data.id');

        $this->assertModelExists($post);
    }

    /** @test */
    public function it_updates_post_tags(): void
    {
        $this->auth('posts.update_any');

        /** @var Post $post */
        $post = Post::factory()->create();

        $tagsString = ' ,, tag 1,tag 2 , , tag 3 ';

        $response = $this->putJson(
            route('api.posts.update', $post),
            ['tags' => $tagsString],
        );

        $response
            ->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('message', Message::success->value)
                    ->has('data.id')
                    ->has('data.tags', 3)
                    ->where('data.tags.0.name', 'tag 1')
                    ->where('data.tags.1.name', 'tag 2')
                    ->where('data.tags.2.name', 'tag 3')
            );
    }

    /**
     * @param array<array<string>> $tags
     *
     * @return Collection<Post>
     */
    private function createPostsWithTags(array $tags): Collection
    {
        /** @var array<Post> $posts */
        foreach ($posts = Post::factory(count($tags))->create() as $index => $post) {
            $post->syncTags($tags[$index]);
        }

        return $posts;
    }
}
