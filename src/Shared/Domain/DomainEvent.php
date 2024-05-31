<?php

declare(strict_types=1);

namespace DDD\Shared\Domain;

interface DomainEvent
{
    public function aggregateId();
    public function occurredOn();
}
