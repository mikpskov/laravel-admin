<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasComments;
use App\Models\Traits\HasLikes;
use App\Models\Traits\HasPublications;
use App\Models\Traits\HasTags;
use App\Models\Traits\HasUser;
use App\Models\Traits\HasVotes;
use App\View\DropdownItem;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $body
 * @property CarbonInterface|null $published_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
final class Post extends Model
{
    use HasFactory;
    use HasUser;
    use HasPublications;
    use HasComments;
    use HasLikes;
    use HasVotes;
    use HasTags;

    protected $with = [
        'user',
    ];

    protected $fillable = [
        'title',
        'body',
    ];

    protected $perPage = 20;

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
