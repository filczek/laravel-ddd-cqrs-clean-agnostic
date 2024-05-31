<?php

declare(strict_types=1);

namespace DDD\Shared\Infrastructure\EventStore;

use DDD\Shared\Domain\EventStream;

interface EventStore
{
    public function append(EventStream $event_stream): void;

    public function eventsFor(string $id): EventStream;
}
