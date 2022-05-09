<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

trait HasTags
{
    public function syncTags(Collection|array $tags): array
    {
        $tags = collect($tags)->map(fn(string $slug) =>
            Tag::query()->firstOrCreate(['slug' => str($slug)->slug()], ['name' => $slug])
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

    public function scopeByTags(Builder $query, Collection|array|null $tags = null): Builder
    {
        if ($tags === null) {
            return $query;
        }

        return $query->whereHas('tags', fn(Builder $query) => $query
            ->whereIn('tags.slug', $tags)
        );
    }

    public function getTagNames(): Collection
    {
        return $this->tags->pluck('name');
    }

    public function tags(): MorphToMany
    {
        return $this
            ->morphToMany(Tag::class, 'taggable')
            ->ordered();
    }
}
