<?php

declare(strict_types=1);

namespace DDD\Post\Application\UseCases\ListPosts;

use DDD\Post\Application\Dto\PostDtoCollection;
use DDD\Post\Application\Dto\PostDtoPaginatedResult;
use DDD\Post\Domain\PostRepository;
use DDD\Shared\Application\Query\QueryHandler;

final class ListPostsQueryHandler implements QueryHandler
{
    public function __construct(
        private PostRepository $posts
    ) {
    }

    public function __invoke(ListPostsQuery $query): PostDtoPaginatedResult
    {
        $result = $this->posts->forPage($query->page, $query->per_page);

        return new PostDtoPaginatedResult(
            data: PostDtoCollection::fromPostCollection($result->posts),
            pagination: $result->pagination
        );
    }
}
