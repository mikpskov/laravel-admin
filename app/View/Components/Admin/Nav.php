<?php

declare(strict_types=1);

namespace App\View\Components\Admin;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Nav extends Component
{
    public function __construct(
        public ?array $items = null,
    ) {
        $this->items ??= $this->getDefaultItems();
    }

    public function render(): View
    {
        return view('admin.components.nav');
    }

    /**
     * @param array<string, string> $item
     */
    public function isActive(array $item): bool
    {
        return url()->current() === $item['link'];
    }

    private function getDefaultItems(): array
    {
        /** @var User|null $user */
        $user = auth()->user();

        $items = [
            [
                'name' => __('Home'),
                'link' => route('home'),
                'permission' => true,
            ],
            [
                'name' => __('Users'),
                'link' => route('admin.users.index'),
                'permission' => $user?->can('viewAny', User::class),
            ],
        ];

        return array_filter($items, fn(array $item) => $item['permission'] ?? false);
    }
}
