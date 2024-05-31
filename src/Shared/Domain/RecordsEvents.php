<?php

declare(strict_types=1);

namespace DDD\Shared\Domain;

use ReflectionClass;

trait RecordsEvents
{
    /** @var DomainEvent[] */
    protected $recorded_events = [];

    /** @return DomainEvent[] */
    public function recordedEvents(): array
    {
        return $this->recorded_events;
    }

    public function clearRecordedEvents(): void
    {
        $this->recorded_events = [];
    }

    protected function recordThat(DomainEvent $event): void
    {
        $this->recorded_events[] = $event;
    }

    protected function applyThat(DomainEvent $event): void
    {
        $reflection = new ReflectionClass($event);

        $event_name = $reflection->getShortName();
        $method_name = "apply{$event_name}";

        $this->{$method_name}($event);
    }

    protected function recordAndApplyThat(DomainEvent $event): void
    {
        $this->recordThat($event);
        $this->applyThat($event);
    }
}
