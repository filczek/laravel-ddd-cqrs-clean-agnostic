<?php

declare(strict_types=1);

namespace DDD\Post\Domain\Exceptions;

use DDD\Shared\Infrastructure\Exceptions\ValidationException;

final class PostCannotBePublished extends ValidationException
{
    public static function dueToEmptyTitle(): self
    {
        return new self("A title is required for published posts. Please ensure a valid title is provided.");
    }

    public static function dueToEmptyContent(): self
    {
        return new self("Published posts require a non-empty content. Please provide a valid content for the post.");
    }

    public static function becauseItHasAlreadyBeenPublished(): self
    {
        return new self("The post has already been published and cannot be published again.");
    }
}
