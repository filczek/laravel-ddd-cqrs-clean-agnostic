<?php

declare(strict_types=1);

namespace DDD\Post\Infrastructure\Persistence;

use DDD\Shared\Infrastructure\PaginationMetadata;

final readonly class PostSnapshotPaginationResult
{
    public function __construct(
        public PostSnapshotCollection $snapshots,
        public PaginationMetadata $pagination
    ) {
    }
}
