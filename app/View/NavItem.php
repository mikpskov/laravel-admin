<?php

declare(strict_types=1);

namespace App\View;

final class NavItem
{
    public bool $show;

    public function __construct(
        public string $name,
        public string $link,
        ?bool $show = null,
    ) {
        $this->show = $show ?? false;
    }
}
