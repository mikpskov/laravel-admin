<?php

declare(strict_types=1);

namespace App\View\Components\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Order extends Component
{
    /**
     * @var array<string, string>
     */
    public array $orders;

    /**
     * @param array<string, string> $orders
     */
    public function __construct(
        array $orders,
        public readonly ?string $selected = null,
    ) {
        $this->setOrders($orders);
    }

    public function render(): View
    {
        return view('admin.components.order');
    }

    /**
     * @param array<string, string> $orders
     */
    private function setOrders(array $orders): void
    {
        foreach ($orders as $column => $name) {
            $this->orders["{$column}_asc"] = "$name ↓";
            $this->orders["{$column}_desc"] = "$name ↑";
        }
    }
}
