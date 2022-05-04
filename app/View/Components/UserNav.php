<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\User;
use App\View\NavItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class UserNav extends Component
{
    /**
     * @param array<NavItem> $items
     */
    public function __construct(
        public readonly User $user,
        public ?array $items = null,
    ) {
        $this->setItems($items ?? $this->getDefaultItems());
    }

    public function render(): View
    {
        return view('components.user-nav');
    }

    public function isActive(NavItem $item): bool
    {
        return url()->current() === $item->link;
    }

    private function setItems(array $items): void
    {
        $this->items = array_filter($items, fn(NavItem $item) => $item->show);
    }

    /**
     * @return array<NavItem>
     */
    private function getDefaultItems(): array
    {
        /** @var User|null $user */
        $user = auth()->user();

        return [
            new NavItem(
                __('Profile'),
                route('users.show', $this->user),
                true,
            ),
            new NavItem(
                __('Posts'),
                route('users.posts', $this->user),
                true,
            ),
            new NavItem(
                __('Comments'),
                route('users.comments', $this->user),
                true,
            ),
            new NavItem(
                __('Saved'),
                route('users.saved_posts', $this->user),
                $user?->can('saved', $this->user),
            ),
            // new NavItem(
            //     __('Saved'),
            //     route('users.saved_comments', $this->user),
            //     $user?->can('saved', $this->user),
            // ),
            new NavItem(
                __('Upvoted'),
                route('users.upvoted_posts', $this->user),
                $user?->can('upvoted', $this->user),
            ),
            // new NavItem(
            //     __('Upvoted'),
            //     route('users.upvoted_comments', $this->user),
            //     $user?->can('upvoted', $this->user),
            // ),
            new NavItem(
                __('Downvoted'),
                route('users.downvoted_posts', $this->user),
                $user?->can('downvoted', $this->user),
            ),
            // new NavItem(
            //     __('Downvoted'),
            //     route('users.downvoted_comments', $this->user),
            //     $user?->can('downvoted', $this->user),
            // ),
            new NavItem(
                __('Notifications'),
                route('users.notifications.index', $this->user),
                $user?->can('notifications', $this->user),
            ),
        ];
    }
}
