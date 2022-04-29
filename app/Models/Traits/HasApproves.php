<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasApproves
{
    public function initializeHasApproves(): void
    {
        $this->mergeCasts([
            $this->getApprovedAtColumn() => 'datetime',
            $this->getApprovedByColumn() => 'int',
        ]);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->whereNotNull($this->getApprovedAtColumn());
    }

    public function scopeUnapproved(Builder $query): Builder
    {
        return $query->whereNull($this->getApprovedAtColumn());
    }

    public function isApproved(): bool
    {
        return $this->{$this->getApprovedAtColumn()} !== null;
    }

    public function approve(): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        $this->{$this->getApprovedAtColumn()} = now();
        $this->{$this->getApprovedByColumn()} = $user?->getKey();

        if ($result = $this->save()) {
            $this->fireModelEvent('approved', false);
        }

        return $result;
    }

    public function disapprove(): bool
    {
        $this->{$this->getApprovedAtColumn()} = null;
        $this->{$this->getApprovedByColumn()} = null;

        if ($result = $this->save()) {
            $this->fireModelEvent('disapproved', false);
        }

        return $result;
    }

    public static function approved(callable $callback): void
    {
        self::registerModelEvent('approved', $callback);
    }

    public static function disapproved(callable $callback): void
    {
        self::registerModelEvent('disapproved', $callback);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, $this->getApprovedByColumn());
    }

    public function getApprovedAtColumn(): string
    {
        return defined('static::APPROVED_AT') ? static::APPROVED_AT : 'approved_at';
    }

    public function getApprovedByColumn(): string
    {
        return defined('static::APPROVED_BY') ? static::APPROVED_BY : 'approved_by';
    }
}
