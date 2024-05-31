<?php

declare(strict_types=1);

namespace DDD\Shared\Domain;

use BadMethodCallException;
use SplFixedArray;

final class EventStream extends SplFixedArray
{
    public function __construct(array $events)
    {
        parent::__construct(count($events));

        $i = 0;
        foreach ($events as $event) {
            parent::offsetSet($i++, $event);
        }
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new BadMethodCallException("EventStream is immutable.");
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new BadMethodCallException("EventStream is immutable.");
    }

}
