<?php

declare(strict_types=1);

namespace Tests\Traits;

use DDD\Shared\Application\Command\CommandBus;
use DDD\Shared\Infrastructure\Bus\InMemoryCommandBus;
use Illuminate\Support\Facades\App;

trait WithInMemoryCommands
{
    public function setupCommandBus(): void
    {
        App::bind(CommandBus::class, InMemoryCommandBus::class, true);
    }

    public function getCommandBus(): InMemoryCommandBus
    {
        return App::make(CommandBus::class);
    }
}
