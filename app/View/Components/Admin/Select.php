<?php

declare(strict_types=1);

namespace App\View\Components\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

final class Select extends Component
{
    /**
     * @param array<string> $options
     */
    public function __construct(
        public string $name,
        public array|Collection $options,
        ?Model $model = null,
        public ?string $selected = null,
        public ?string $label = null,
        public ?string $empty = null,
        public ?string $placeholder = null,
    ) {
        $this->selected ??= old($name) ?? $model?->{$name} ?? null;
    }

    public function render(): View
    {
        return view('admin.components.select');
    }

    public function isSelected(string $value): bool
    {
        return $value === $this->selected;
    }
}
