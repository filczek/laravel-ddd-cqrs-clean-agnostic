<?php

declare(strict_types=1);

namespace DDD\Post\Domain\Exceptions;

use InvalidArgumentException;

final class PostContentCannotBeChanged extends InvalidArgumentException
{
    public static function emptyContentNotAllowedWhenPublished(): self
    {
        throw new self("Published posts require a non-empty content. Please provide a valid content for the post.");
    }
}
