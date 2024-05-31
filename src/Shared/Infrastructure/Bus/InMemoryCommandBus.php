<?php

declare(strict_types=1);

namespace DDD\Shared\Infrastructure\Bus;

use DDD\Shared\Application\Command\Command;
use DDD\Shared\Application\Command\CommandBus;

class InMemoryCommandBus implements CommandBus
{
    /** @var Command[] */
    private $commands = [];

    public function handle(Command $command): mixed
    {
        $this->commands[] = $command;

        return null;
    }

    /** @return Command[] */
    public function all(): array
    {
        return $this->commands;
    }

    public function wasHandled(string $command): bool
    {
        foreach ($this->commands as $handled_command) {
            if ($handled_command instanceof $command) {
                return true;
            }
        }

        return false;
    }

    public function wasHandledTimes(string $command, int $times): bool
    {
        $amount = 0;

        foreach ($this->commands as $handled_command) {
            if ($handled_command instanceof $command) {
                $amount++;
            }
        }

        return $amount === $times;
    }

    public function wasHandledOnce(string $command): bool
    {
        return $this->wasHandledTimes($command, 1);
    }
}
