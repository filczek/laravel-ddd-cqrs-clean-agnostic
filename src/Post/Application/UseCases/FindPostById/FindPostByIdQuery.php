<?php

declare(strict_types=1);

namespace DDD\Post\Application\UseCases\FindPostById;

use DDD\Shared\Application\Query\Query;

final readonly class FindPostByIdQuery implements Query
{
    public static function fromArray(array $array): self
    {
        return new self(
            id: $array['id']
        );
    }

    public function __construct(
        public string $id,
    ) {
    }
}
