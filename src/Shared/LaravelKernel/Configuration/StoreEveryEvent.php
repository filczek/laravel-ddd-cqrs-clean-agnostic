<?php

declare(strict_types=1);

namespace DDD\Shared\LaravelKernel\Configuration;

use DDD\Shared\Application\Event\EventHandler;
use DDD\Shared\Domain\DomainEvent;
use DDD\Shared\Domain\EventStream;
use DDD\Shared\Infrastructure\EventStore\RedisEventStore;

class StoreEveryEvent implements EventHandler
{
    public function __construct(
        private RedisEventStore $store
    ) {
    }

    public function __invoke(DomainEvent $event): void
    {
        $stream = new EventStream([$event]);

        $this->store->append($stream);
    }
}
