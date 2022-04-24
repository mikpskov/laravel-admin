<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasApproves;
use App\Models\Traits\HasLikes;
use App\View\DropdownItem;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $author_id
 * @property int $post_id
 * @property string $body
 * @property CarbonInterface|null $approved_at
 * @property int $approved_by
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property CarbonInterface|null $deleted_at
 * @property User $author
 * @property Post $post
 * @property User $approver
 */
final class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasLikes;
    use HasApproves;

    protected $with = [
        'author',
    ];

    protected $fillable = [
        'body',
    ];

    protected $casts = [
        'author_id' => 'int',
        'post_id' => 'int',
    ];

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

    public function scopeByPostId(Builder $query, int $postId): Builder
    {
        return $query->where('post_id', $postId);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function getActions(): array
    {
        /** @var User|null $user */
        $user = auth()->user();

        return [
            new DropdownItem(
                $this->isApproved() ? __('Disapprove') : __('Approve'),
                route('admin.comments.approve', $this),
                $user?->can('approve', $this),
                $this->isApproved() ? 'DELETE' : 'PATCH',
            ),
            new DropdownItem(
                __('Delete'),
                route('admin.comments.destroy', $this),
                $user?->can('delete', $this),
                'DELETE',
            ),
        ];
    }
}
