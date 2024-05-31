<?php

declare(strict_types=1);

namespace DDD\Shared\Infrastructure\Serializer;

use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

final class JsonSerializer implements SerializerInterface
{
    private Serializer $serializer;

    public function __construct()
    {
        $normalizers = [new PropertyNormalizer()];
        $encoders = [new JsonEncoder()];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function serialize(mixed $data): string
    {
        if (false === is_object($data)) {
            throw new InvalidArgumentException("Data must be an object.");
        }

        $json = $this->serializer->serialize($data, 'json');

        $reflection = new ReflectionClass($data);

        // Symfony's Serializer does not include the class
        // Decode the serialized object and add its type to it to support deserializing
        $json = json_decode(json: $json, associative: true);
        $json = ['type' => $reflection->getName(), 'name' =>  $reflection->getShortName(), 'props' => $json];
        $json = json_encode(value: $json, flags: JSON_PRESERVE_ZERO_FRACTION);

        return $json;
    }

    public function deserialize(mixed $data): mixed
    {
        $decoded = json_decode(json: $data, associative: true);

        $type = $decoded['type'] ?? null;
        if (null === $type) {
            throw new InvalidArgumentException("Deserialized data must come from serialized object.");
        }

        $data = $decoded['props'];
        $data = json_encode($data, JSON_PRESERVE_ZERO_FRACTION);

        return $this->serializer->deserialize(data: $data, type: $type, format: 'json');
    }
}
