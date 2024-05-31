<?php

declare(strict_types=1);

namespace DDD\Post\Domain\Exceptions;

use DDD\Shared\Infrastructure\Exceptions\RecordNotFoundException;

final class PostNotFound extends RecordNotFoundException
{
    public static function withIdOf(mixed $id): self
    {
        return new self("Post with ID '$id' was not found.");
    }
}
