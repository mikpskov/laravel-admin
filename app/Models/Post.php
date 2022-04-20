<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasPublications;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $author_id
 * @property string $title
 * @property string $body
 * @property CarbonInterface|null $published_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property User $author
 */
final class Post extends Model
{
    use HasFactory;
    use HasPublications;

    protected $fillable = [
        'title',
        'body',
    ];

    protected $casts = [
        'author_id' => 'int',  // todo: HasAuthor trait
    ];

    protected $perPage = 20;

    protected static function booted(): void
    {
        /** @var User $user */
        if ($user = auth()->user()) {
            self::creating(fn(self $model) => $model->author_id ??= $user->id);
        }
    }

    public function scopeByAuthor(Builder $query, User $user): Builder
    {
        return $query->where('author_id', $user->id);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPublishLink(): string
    {
        return route('admin.posts.publish', $this);
    }

    public function getEditLink(): string
    {
        return route('admin.posts.edit', $this);
    }

    public function getRemoveLink(): string
    {
        return route('admin.posts.destroy', $this);
    }
}
