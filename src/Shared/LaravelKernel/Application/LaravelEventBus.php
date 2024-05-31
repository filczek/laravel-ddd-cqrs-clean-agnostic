<?php

declare(strict_types=1);

namespace DDD\Shared\LaravelKernel\Application;

use DDD\Shared\Application\Event\EventBus;
use DDD\Shared\Domain\DomainEvent;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;

final class LaravelEventBus implements EventBus
{
    public function publish(DomainEvent $event): void
    {
        $domain_event_listeners = Event::getRawListeners()[DomainEvent::class] ?? [];
        $specific_event_listeners = Event::getRawListeners()[$event::class] ?? [];

        $listeners = [...$domain_event_listeners, ...$specific_event_listeners];

        foreach ($listeners as $listener) {
            dispatch(fn () => App::make($listener)($event))
                ->onConnection('rabbitmq')
                ->onQueue('events');
        }
    }

    public function publishAll(iterable $events): void
    {
        foreach ($events as $event) {
            $this->publish($event);
        }
    }
}
