<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasLikes;
use App\Models\Traits\HasPublications;
use App\View\DropdownItem;
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
    use HasLikes;

    protected $with = [
        'author',
    ];

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

    public function scopeByAuthorId(Builder $query, int $authorId): Builder
    {
        return $query->where('author_id', $authorId);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActions(): array
    {
        /** @var User|null $user */
        $user = auth()->user();

        return [
            new DropdownItem(
                __('Edit'),
                route('admin.posts.edit', $this),
                $user?->can('update', $this),
                'GET',
            ),
            new DropdownItem(
                $this->isPublished() ? __('Unpublish') : __('Publish'),
                route('admin.posts.publish', $this),
                $user?->can('publish', $this),
                $this->isPublished() ? 'DELETE' : 'PATCH',
            ),
            new DropdownItem(
                __('Delete'),
                route('admin.posts.destroy', $this),
                $user?->can('delete', $this),
                'DELETE',
            ),
        ];
    }
}
