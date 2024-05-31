<?php

declare(strict_types=1);

namespace DDD\Shared\Application;

use DDD\Shared\Application\Command\Command;
use DDD\Shared\Application\Command\CommandBus;
use DDD\Shared\Application\Query\Query;
use DDD\Shared\Application\Query\QueryBus;
use Illuminate\Support\Facades\App;

abstract class Module
{
    private CommandBus $command_bus;
    private QueryBus $query_bus;

    public function __construct() {
        $this->command_bus = App::make(CommandBus::class);
        $this->query_bus = App::make(QueryBus::class);
    }

    protected function handle(Command $command): mixed
    {
        return $this->command_bus->handle($command);
    }

    protected function execute(Query $query): mixed
    {
        return $this->query_bus->execute($query);
    }
}
