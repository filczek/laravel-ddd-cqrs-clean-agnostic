<?php

declare(strict_types=1);

namespace DDD\Shared\LaravelKernel\Application;

use DDD\Shared\Application\Command\Command;
use DDD\Shared\Application\Command\CommandHandlerAsync;
use DDD\Shared\Application\Command\CommandBus;
use Illuminate\Support\Facades\App;

final class LaravelCommandBus implements CommandBus
{
    public function handle(Command $command): mixed
    {
        $handler = $this->makeCommandHandler($command);

        if ($handler instanceof CommandHandlerAsync) {
            return $this->handleCommandAsynchronously($command);
        }

        return $this->handleCommand($command, $handler);
    }

    private function makeCommandHandler(Command $command): mixed
    {
        $handler_class = $command::class . "Handler";
        return App::make($handler_class);
    }

    private function handleCommand(Command $command, mixed $handler = null): mixed
    {
        $handler = $handler ?? $this->makeCommandHandler($command);
        return $handler($command);
    }

    private function handleCommandAsynchronously(Command $command): null
    {
        dispatch(fn () => $this->handleCommand($command))
            ->onConnection('rabbitmq')
            ->onQueue('commands');

        return null;
    }
}
