<?php

declare(strict_types=1);

namespace DDD\Post\Application\Dto;

use DDD\Post\Domain\PostCollection;
use Illuminate\Support\Collection;

/**
 * @extends Collection<int, PostDto>
 */
final class PostDtoCollection extends Collection
{
    public static function fromPostCollection(PostCollection $posts): self
    {
        $dto_collection = self::empty();

        foreach ($posts as $post) {
            $dto_collection->push(PostDto::fromPost($post));
        }

        return $dto_collection;
    }
}
