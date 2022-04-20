<?php

declare(strict_types=1);

namespace App\View\Components\Admin;

use App\View\DropdownItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Dropdown extends Component
{
    /**
     * @param array<DropdownItem> $items
     */
    public function __construct(
        public string $name,
        public array $items,
    ) {
        $this->setItems($items);
    }

    public function render(): View
    {
        return view('admin.components.dropdown');
    }

    private function setItems(array $items): void
    {
        $this->items = array_filter($items, fn(DropdownItem $item) => $item->show);
    }
}
