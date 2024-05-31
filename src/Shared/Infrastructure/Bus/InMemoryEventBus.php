<?php

declare(strict_types=1);

namespace DDD\Shared\Infrastructure\Bus;

use DDD\Shared\Application\Event\EventBus;
use DDD\Shared\Domain\DomainEvent;

class InMemoryEventBus implements EventBus
{
    /** @var DomainEvent[] */
    private $events = [];

    public function publish(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    public function publishAll(iterable $events): void
    {
        foreach ($events as $event) {
            $this->publish($event);
        }
    }

    /** @return DomainEvent[] */
    public function all(): iterable
    {
        return $this->events;
    }

    public function clear(): void
    {
        $this->events = [];
    }

    public function wasPublishedTimes(string $event, int $times): bool
    {
        $amount = 0;

        foreach ($this->all() as $published_event) {
            if ($published_event instanceof $event) {
                $amount++;
            }
        }

        return $amount === $times;
    }

    public function wasPublishedOnce(string $event): bool
    {
        return $this->wasPublishedTimes($event, 1);
    }
}
