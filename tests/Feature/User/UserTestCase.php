<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use Tests\Feature\TestCase;

abstract class UserTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->auth();
    }
}
