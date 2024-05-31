<?php

declare(strict_types=1);

namespace DDD\Post\Infrastructure\Persistence;

final readonly class PostSnapshot
{
    public function __construct(
        public string $id,
        public string $version,
        public string $state,
        public string $title,
        public string $content,
        public string $created_at,
        public ?string $published_at,
        public ?string $deleted_at
    ) {
    }
}
