<?php

declare(strict_types=1);

namespace DDD\Post\Domain\ValueObjects;

use Symfony\Component\String\UnicodeString;

final class PostContent extends UnicodeString
{
    public static function from(string $value): self
    {
        $content = new self($value);

        return $content->trim();
    }
}
