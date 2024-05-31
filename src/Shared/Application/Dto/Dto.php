<?php

declare(strict_types=1);

namespace DDD\Shared\Application\Dto;

use JsonSerializable;

abstract readonly class Dto implements JsonSerializable
{
    public function jsonSerialize(): array
    {
        return (array) $this;
    }
}
