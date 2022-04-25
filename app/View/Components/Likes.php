<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Likes extends Component
{
    public function __construct(
        public readonly string $type,  // todo: check type
        public readonly int $id,
        public readonly bool $active,
        public readonly int $count = 0,
    ) {
    }

    public function render(): View
    {
        return view('components.likes');
    }
}
