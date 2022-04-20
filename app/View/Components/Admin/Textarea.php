<?php

declare(strict_types=1);

namespace App\View\Components\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

final class Textarea extends Component
{
    public function __construct(
        public string $name,
        ?Model $model = null,
        public ?string $value = null,
        public ?string $label = null,
        public ?string $placeholder = null,
    ) {
        $this->value ??= old($name) ?? $model?->{$name} ?? '';
    }

    public function render(): View
    {
        return view('admin.components.textarea');
    }
}
