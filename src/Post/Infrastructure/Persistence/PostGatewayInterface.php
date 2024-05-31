<?php

declare(strict_types=1);

namespace DDD\Post\Infrastructure\Persistence;

interface PostGatewayInterface
{
    public function create(PostSnapshot $snapshot): void;

    public function ofId(string $id): PostSnapshot;

    public function forPage(int $page, int $per_page): PostSnapshotPaginationResult;

    public function update(PostSnapshot $snapshot, string $previous_version): void;
}
