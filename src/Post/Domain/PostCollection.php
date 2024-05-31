<?php

declare(strict_types=1);

namespace DDD\Post\Domain;

use DDD\Post\Infrastructure\Persistence\PostSnapshotCollection;
use Illuminate\Support\Collection;

/** @extends Collection<int, Post> */
final class PostCollection extends Collection
{
    public static function fromSnapshotCollection(PostSnapshotCollection $snapshots): self
    {
        $aggregates = self::empty();

        foreach ($snapshots as $snapshot) {
            $aggregates->add(Post::fromSnapshot($snapshot));
        }

        return $aggregates;
    }
}
