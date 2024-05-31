<?php

declare(strict_types=1);

namespace DDD\Post\Application\Dto;

use DDD\Shared\Infrastructure\PaginationMetadata;
use JsonSerializable;

final readonly class PostDtoPaginatedResult implements JsonSerializable
{
    public function __construct(
        public PostDtoCollection $data,
        public PaginationMetadata $pagination
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'data' => $this->data,
            'metadata' => [
                'pagination' => $this->pagination,
            ],
        ];
    }
}
