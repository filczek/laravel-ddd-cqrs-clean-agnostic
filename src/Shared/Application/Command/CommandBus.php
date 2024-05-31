<?php

declare(strict_types=1);

namespace DDD\Shared\Application\Command;

interface CommandBus
{
    public function handle(Command $command): mixed;
}
