<?php

declare(strict_types=1);

namespace App\Services\Post;

use App\Models\Post;

final class PostManager
{
    public function store(array $data): Post
    {
        /** @var Post $post */
        $post = Post::create($data);

        if ($tagsString = $data['tags'] ?? null) {
            $this->syncTags($post, $tagsString);
        }

        return $post;
    }

    public function update(Post $post, array $data): Post
    {
        $post->update($data);

        if ($tagsString = $data['tags'] ?? null) {
            $this->syncTags($post, $tagsString);
        }

        return $post;
    }

    public function publish(Post $post): Post
    {
        $post->togglePublish(true)->save();

        return $post;
    }

    public function unpublish(Post $post): Post
    {
        $post->togglePublish(false)->save();

        return $post;
    }

    public function delete(Post $post): void
    {
        $post->delete();
    }

    private function syncTags(Post $post, string $tagsString): void
    {
        $tags = collect(explode(',', $tagsString))
            ->map(fn(string $slug) => trim($slug))
            ->filter();

        $post->syncTags($tags);
        $post->load('tags');
    }
}
