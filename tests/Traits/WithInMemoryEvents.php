<?php

declare(strict_types=1);

namespace Tests\Traits;

use DDD\Shared\Application\Event\EventBus;
use DDD\Shared\Infrastructure\Bus\InMemoryEventBus;
use Illuminate\Support\Facades\App;

trait WithInMemoryEvents
{
    public function setupEventBus(): void
    {
        App::bind(EventBus::class, InMemoryEventBus::class, true);
    }

    public function getEventBus(): InMemoryEventBus
    {
        return App::make(EventBus::class);
    }

    public function assertCountOfDispatchedEvents(int $count): void
    {
        $this->assertCount($count, $this->getEventBus()->all());
    }

    public function assertEventWasPublishedOnce(string $command): void
    {
        $this->assertTrue($this->getEventBus()->wasPublishedOnce($command));
    }

    public function assertEventWasNotPublished(string $command): void
    {
        $this->assertTrue($this->getEventBus()->wasPublishedTimes($command, 0));
    }
}
