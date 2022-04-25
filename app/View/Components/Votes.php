<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

final class Votes extends Component
{
    public int $total;
    public int $count;

    public function __construct(
        public readonly string $type,  // todo: check type
        public readonly Model $model,
    ) {
        $this->total = $model->votes_up_count - $model->votes_down_count;
        $this->count = $model->votes_up_count + $model->votes_down_count;
    }

    public function render(): View
    {
        return view('components.votes');
    }
}
