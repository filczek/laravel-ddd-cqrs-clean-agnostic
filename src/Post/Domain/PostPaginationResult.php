<?php

declare(strict_types=1);

namespace DDD\Post\Domain;

use DDD\Shared\Infrastructure\PaginationMetadata;

final readonly class PostPaginationResult
{
    public function __construct(
        public PostCollection $posts,
        public PaginationMetadata $pagination,
    ) {
    }
}
