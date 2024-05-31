<?php

declare(strict_types=1);

namespace DDD\Post\Infrastructure\Persistence\Eloquent;

use DDD\Post\Domain\Enums\PostState;
use DDD\Post\Domain\Exceptions\PostAlreadyExists;
use DDD\Post\Domain\Exceptions\PostNotFound;
use DDD\Post\Infrastructure\Persistence\PostGatewayInterface;
use DDD\Post\Infrastructure\Persistence\PostSnapshot;
use DDD\Post\Infrastructure\Persistence\PostSnapshotCollection;
use DDD\Post\Infrastructure\Persistence\PostSnapshotPaginationResult;
use DDD\Shared\Infrastructure\Exceptions\ConcurrencyException;
use DDD\Shared\Infrastructure\PaginationMetadata;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;

final class EloquentPostGateway implements PostGatewayInterface
{
    public function create(PostSnapshot $snapshot): void
    {
        try {
            Post::create((array) $snapshot)
                ->saveOrFail();
        } catch (UniqueConstraintViolationException) {
            throw PostAlreadyExists::withIdOf($snapshot->id);
        }
    }

    public function ofId(string $id): PostSnapshot
    {
        try {
            return Post::whereId($id)
                ->firstOrFail()
                ->toSnapshot();
        } catch (ModelNotFoundException) {
            throw PostNotFound::withIdOf($id);
        }
    }

    public function forPage(int $page, int $per_page): PostSnapshotPaginationResult
    {
        $records = Post::whereState(PostState::Published)
            ->latest('published_at')
            ->paginate(perPage: $per_page, page: $page);

        $snapshots = PostSnapshotCollection::make(
            $records->map(fn ($post) => $post->toSnapshot())
        );

        $pagination = new PaginationMetadata(
            page: $page,
            per_page: $per_page,
            total_items: $records->total(),
            total_pages: $records->lastPage()
        );

        return new PostSnapshotPaginationResult(
            snapshots: $snapshots,
            pagination: $pagination
        );
    }

    public function update(PostSnapshot $snapshot, string $previous_version): void
    {
        $affected_records = Post::whereId($snapshot->id)
            ->whereVersion($previous_version)
            ->update((array) $snapshot);

        if ($affected_records === 1) {
            // Update successful, record was updated
            return;
        }

        if ($this->ofId($snapshot->id)->version === $snapshot->version) {
            // Update successful, nothing changed
            return;
        }

        throw new ConcurrencyException("Failed to update post (ID: {$snapshot->id}) because the version has changed since the last read.");
    }

}
