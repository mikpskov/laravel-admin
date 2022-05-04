<?php

declare(strict_types=1);

namespace App\View\Components\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class PerPage extends Component
{
    /**
     * @param array<string> $items
     */
    public function __construct(
        public readonly string $name,
        public array $items,
        public readonly ?string $selected = null,
    ) {
        $this->setItems($items);
    }

    public function render(): View
    {
        return view('admin.components.per-page');
    }

    /**
     * @param array<string> $items
     */
    private function setItems(array $items): void
    {
        $this->items = array_combine($items, $items);
    }
}
