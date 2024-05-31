<?php

declare(strict_types=1);

namespace DDD\Post\Interface\Api;

use DDD\Post\Application\PostModule;
use DDD\Post\Application\UseCases\FindPostById\FindPostByIdQuery;
use DDD\Post\Application\UseCases\UpdatePost\UpdatePostCommand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final readonly class UpdatePost
{
    public function __construct(
        private PostModule $posts
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $id = $request->route('id');

        $this->posts->update(
            UpdatePostCommand::fromArray([...$request->all(), 'id' => $id])
        );

        $post = $this->posts->ofId(
            FindPostByIdQuery::fromArray(['id' => $id])
        );

        return response($post, Response::HTTP_OK);
    }
}
