<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasTags
{
    public function syncTags(string $tagsString): array
    {
        $slugs = collect(explode(',', $tagsString))
            ->map(fn(string $slug) => trim($slug))
            ->filter();

        $tags = $slugs->map(fn(string $slug) =>
            Tag::query()->firstOrCreate(['slug' => $slug, 'name' => $slug])
        );

        return $this->tags()->sync($tags->pluck('id')->toArray());
    }

    public function scopeByTag(Builder $query, ?string $slug = null): Builder
    {
        if ($slug === null) {
            return $query;
        }

        return $query->whereHas('tags', fn($query) => $query
            ->where('tags.slug', $slug)
        );
    }

    public function tags(): MorphToMany
    {
        return $this
            ->morphToMany(Tag::class, 'taggable')
            ->ordered();
    }
}
