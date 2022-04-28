<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Tag extends Model
{
    use HasFactory;

    // todo: trait HasOrder
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }
}
