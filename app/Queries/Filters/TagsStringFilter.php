<?php

declare(strict_types=1);

namespace App\Queries\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

final class TagsStringFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->byTags(collect(is_array($value) ? $value : [$value])
            ->map(fn(string $slug) => trim($slug))
            ->filter()
        );
    }
}
