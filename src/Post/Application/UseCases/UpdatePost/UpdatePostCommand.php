<?php

declare(strict_types=1);

namespace DDD\Post\Application\UseCases\UpdatePost;

use DDD\Shared\Application\Command\Command;

final readonly class UpdatePostCommand implements Command
{
    public static function fromArray(array $array): self
    {
        return new self(
            id: $array['id'],
            version: $array['version'],
            title: $array['title'] ?? null,
            content: $array['content'] ?? null,
        );
    }

    public function __construct(
        public string $id,
        public string $version,
        public ?string $title = null,
        public ?string $content = null,
    ) {
    }
}
