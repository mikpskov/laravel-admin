<?php

declare(strict_types=1);

namespace App\View\Components\Admin;

use App\Models\Post;
use App\Models\User;
use App\View\NavItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Nav extends Component
{
    /**
     * @param array<NavItem> $items
     */
    public function __construct(
        public ?array $items = null,
    ) {
        $this->setItems($items ?? $this->getDefaultItems());
    }

    public function render(): View
    {
        return view('admin.components.nav');
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
                __('Home'),
                route('home'),
                true,
            ),
            new NavItem(
                __('Users'),
                route('admin.users.index'),
                $user?->can('viewAny', User::class),
            ),
            new NavItem(
                __('Posts'),
                route('admin.posts.index'),
                $user?->can('viewAny', Post::class),
            ),
        ];
    }
}
