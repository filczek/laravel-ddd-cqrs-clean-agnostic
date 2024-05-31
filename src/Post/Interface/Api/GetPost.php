<?php

declare(strict_types=1);

namespace DDD\Post\Interface\Api;

use DDD\Post\Application\PostModule;
use DDD\Post\Application\UseCases\FindPostById\FindPostByIdQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final readonly class GetPost
{
    public function __construct(
        private PostModule $posts
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $post = $this->posts->ofId(
            FindPostByIdQuery::fromArray($request->route()->parameters())
        );

        return response($post, Response::HTTP_OK);
    }
}
