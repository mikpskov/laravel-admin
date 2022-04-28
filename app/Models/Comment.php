<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasApproves;
use App\Models\Traits\HasLikes;
use App\Models\Traits\HasUser;
use App\Models\Traits\HasVotes;
use App\View\DropdownItem;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property string $body
 * @property CarbonInterface|null $approved_at
 * @property int $approved_by
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property CarbonInterface|null $deleted_at
 * @property Post $post
 * @property User $approver
 */
final class Comment extends Model
{
    use HasFactory;
    use HasUser;
    use HasLikes;
    use HasVotes;
    use HasApproves;
    use SoftDeletes;

    protected $with = [
        'user',
    ];

    protected $fillable = [
        'body',
    ];

    protected $casts = [
        'post_id' => 'int',
    ];

    public function scopeByPostId(Builder $query, int $postId): Builder
    {
        return $query->where('post_id', $postId);
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
