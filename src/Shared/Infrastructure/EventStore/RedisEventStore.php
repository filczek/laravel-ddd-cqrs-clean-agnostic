<?php

declare(strict_types=1);

namespace DDD\Shared\Infrastructure\EventStore;

use DDD\Shared\Domain\DomainEvent;
use DDD\Shared\Domain\EventStream;
use DDD\Shared\Infrastructure\Serializer\JsonSerializer;
use Illuminate\Redis\Connections\Connection;
use Redis;
use Throwable;

final readonly class RedisEventStore implements EventStore
{
    private Redis $redis;

    public function __construct(
        private Connection $connection,
        private JsonSerializer $serializer,
    ) {
        $this->redis = $this->connection->client();
    }

    public function append(EventStream $event_stream): void
    {
        $tx = $this->redis->multi();

        try {
            /** @var DomainEvent $event */
            foreach ($event_stream as $event) {
                $key = $this->getKey($event->aggregateId());
                $json = $this->serializer->serialize($event);

                $tx->rpush($key, $json);
            }

            $tx->exec();
        } catch (Throwable $e) {
            $tx->discard();
            throw $e;
        }
    }

    public function eventsFor(string $id): EventStream
    {
        return $this->fromVersion($id);
    }

    private function getKey($id): string
    {
        return "events:$id";
    }

    private function fromVersion(string $id, int $version = 0): EventStream
    {
        $serialized_events = $this->redis
            ->lrange($this->getKey($id), $version, -1);

        /** @var DomainEvent[] $events */
        $events = [];
        foreach ($serialized_events as $event) {
            $events[] = $this->serializer->deserialize($event);
        }

        return new EventStream($events);
    }
}
