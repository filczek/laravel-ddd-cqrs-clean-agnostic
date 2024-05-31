<?php

declare(strict_types=1);

namespace DDD\Shared\Infrastructure;

final readonly class PaginationMetadata
{
    public function __construct(
        public int $page,
        public int $per_page,
        public int $total_items,
        public int $total_pages,
    ) {
    }
}
