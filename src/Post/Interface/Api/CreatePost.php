<?php

declare(strict_types=1);

namespace DDD\Post\Interface\Api;

use DDD\Post\Application\PostModule;
use DDD\Post\Application\UseCases\CreatePost\CreatePostCommand;
use DDD\Post\Application\UseCases\FindPostById\FindPostByIdQuery;
use DDD\Post\Domain\ValueObjects\PostId;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final readonly class CreatePost
{
    public function __construct(
        private PostModule $posts
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $id = PostId::nextIdentity()->toString();

        $this->posts->create(
            CreatePostCommand::fromArray(['id' => $id, ...$request->all()])
        );

        $post = $this->posts->ofId(
            FindPostByIdQuery::fromArray(['id' => $id])
        );

        return response($post, Response::HTTP_OK);
    }
}
