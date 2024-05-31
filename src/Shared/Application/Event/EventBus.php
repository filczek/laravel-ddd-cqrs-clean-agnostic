<?php

declare(strict_types=1);

namespace DDD\Shared\Application\Event;

use DDD\Shared\Domain\DomainEvent;

interface EventBus
{
    public function publish(DomainEvent $event): void;

    /** @param DomainEvent[] $events */
    public function publishAll(iterable $events): void;
}
