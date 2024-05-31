<?php

declare(strict_types=1);

namespace DDD\Shared\Infrastructure\Serializer;

interface SerializerInterface
{
    public function serialize(mixed $data): string;

    public function deserialize(mixed $data): mixed;
}
