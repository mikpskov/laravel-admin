<?php

declare(strict_types=1);

namespace App\Queries;

use App\Queries\Filters\TagsStringFilter;

final class PostQuery extends Query
{
    protected const FIELDS = [
        'id',
        'title',
        'body',
        'created_at',
        'updated_at',
    ];

    protected const INCLUDES = [
        'user',
        'tags',
    ];

    protected const FILTERS_PARTIAL = [
        'title',
    ];

    protected const FILTERS_EXACT = [
        'user_id',
    ];

    protected const FILTERS_SCOPE = [
        'published',
        'unpublished',
    ];

    protected const FILTERS_CUSTOM = [
        'tags' => TagsStringFilter::class,
    ];

    protected const SORTS = [
        'id',
        'title',
        'created_at',
        'updated_at',
    ];
}
