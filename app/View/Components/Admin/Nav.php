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
        $items = [
            [
                'name' => __('Home'),
                'link' => route('home'),
            ],
            [
                'name' => __('Users'),
                'link' => route('admin.users.index'),
                'permission' => 'users.viewAny',
            ],
        ];

        /** @var User|null $user */
        $user = auth()->user();

        return array_filter($items, function (array $item) use ($user) {
            if (!array_key_exists('permission', $item)) {
                return true;
            }

            if ($user === null || $user->cannot($item['permission'])) {
                return false;
            }

            return true;
        });
    }
}
