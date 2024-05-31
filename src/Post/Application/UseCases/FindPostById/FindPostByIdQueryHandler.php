<?php

declare(strict_types=1);

namespace DDD\Post\Application\UseCases\FindPostById;

use DDD\Post\Application\Dto\PostDto;
use DDD\Post\Domain\Exceptions\PostNotFound;
use DDD\Post\Domain\PostRepository;
use DDD\Post\Domain\ValueObjects\PostId;
use DDD\Shared\Application\Query\QueryHandler;

final readonly class FindPostByIdQueryHandler implements QueryHandler
{
    public function __construct(
        private PostRepository $posts
    ) {
    }

    public function __invoke(FindPostByIdQuery $query): PostDto
    {
        $id = PostId::from($query->id);

        $post = $this->posts->ofId($id);

        if ($post->isDeleted()) {
            throw PostNotFound::withIdOf($id);
        }

        return PostDto::fromPost($post);
    }
}
