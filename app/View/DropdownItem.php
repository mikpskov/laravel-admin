<?php

declare(strict_types=1);

namespace App\View;

final class DropdownItem
{
    public bool $show;

    public function __construct(
        public string $name,
        public string $link,
        ?bool $show = null,
        public string $method = 'GET',
    ) {
        $this->show = $show ?? false;
    }
}
