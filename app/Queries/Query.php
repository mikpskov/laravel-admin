<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

abstract class Query extends QueryBuilder
{
    public function __construct(?Model $model = null, ?Request $request = null)
    {
        $model ??= new Post();

        parent::__construct($model::query(), $request);

        $this->allowedFields(defined('static::FIELDS') ? static::FIELDS : []);
        $this->allowedIncludes(defined('static::INCLUDES') ? static::INCLUDES : []);
        $this->allowedSorts(defined('static::SORTS') ? static::SORTS : []);

        $filterTypes = collect([
            'partial' => 'static::FILTERS_PARTIAL',
            'exact' => 'static::FILTERS_EXACT',
            'scope' => 'static::FILTERS_SCOPE',
            'custom' => 'static::FILTERS_CUSTOM',
        ])->filter(fn($constant) => defined($constant))
            ->map(fn($constant) => constant($constant));

        $allowedFilters = [];
        foreach ($filterTypes->only('partial', 'exact', 'scope') as $type => $filters) {
            $allowedFilters = collect($filters)
                ->map(fn(string $name, int|string $alias) =>
                    AllowedFilter::$type(is_string($alias) ? $alias : $name, $name)
                )
                ->merge($allowedFilters);
        }

        if (defined('static::FILTERS_CUSTOM')) {
            foreach (static::FILTERS_CUSTOM as $alias => $filterClass) {
                $allowedFilters->add(AllowedFilter::custom($alias, new $filterClass()));
            }
        }

        $this->allowedFilters($allowedFilters->toArray());
    }
}
