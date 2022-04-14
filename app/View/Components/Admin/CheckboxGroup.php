<?php

declare(strict_types=1);

namespace App\View\Components\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

final class CheckboxGroup extends Component
{
    /**
     * @param array<string> $items
     */
    public function __construct(
        public string $name,
        public array|Collection $items,
        public ?Collection $selected = null,
        public ?string $label = null,
    ) {
        $this->selected ??= collect(old($name));
    }

    public function render(): View
    {
        return view('admin.components.checkbox-group');
    }

    public function isChecked(string $value): bool
    {
        return $this->selected?->contains($value) ?: false;
    }
}
