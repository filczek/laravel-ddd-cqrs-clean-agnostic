<?php

declare(strict_types=1);

namespace DDD\Post\Application\UseCases\PublishPost;

use DDD\Shared\Application\Command\Command;

final readonly class PublishPostCommand implements Command
{
    public static function fromArray(array $array): self
    {
        return new self(
            id: $array['id'],
        );
    }

    public function __construct(
        public string $id,
    ) {
    }
}
