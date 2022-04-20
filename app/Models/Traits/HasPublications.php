<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasPublications
{
    public function initializeHasPublications(): void
    {
        $this->mergeCasts([
            $this->getPublishedAtColumn() => 'datetime',
        ]);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull($this->getPublishedAtColumn());
    }

    public function scopeUnpublished(Builder $query): Builder
    {
        return $query->whereNull($this->getPublishedAtColumn());
    }

    public function isPublished(): bool
    {
        return $this->{$this->getPublishedAtColumn()} !== null;
    }

    public function publish(): bool
    {
        $this->{$this->getPublishedAtColumn()} = now();

        if ($result = $this->save()) {
            $this->fireModelEvent('published', false);
        }

        return $result;
    }

    public function unpublish(): bool
    {
        $this->{$this->getPublishedAtColumn()} = null;

        if ($result = $this->save()) {
            $this->fireModelEvent('unpublished', false);
        }

        return $result;
    }

    public static function published(callable $callback): void
    {
        static::registerModelEvent('published', $callback);
    }

    public static function unpublished(callable $callback): void
    {
        static::registerModelEvent('unpublished', $callback);
    }

    public function getPublishedAtColumn(): string
    {
        return defined('static::PUBLISHED_AT') ? static::PUBLISHED_AT : 'published_at';
    }
}
