<?php

declare(strict_types=1);

namespace DDD\Post\Interface\Api;

use DDD\Post\Application\PostModule;
use DDD\Post\Application\UseCases\ListPosts\ListPostsQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final readonly class ListPosts
{
    public function __construct(
        private PostModule $posts
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $values = [
            ...$request->all(),
            'page' => (int) $request->get('page', 1),
            'per_page' => (int) $request->get('per_page', 15),
        ];

        $posts = $this->posts->paginate(
            ListPostsQuery::fromArray($values)
        );

        return response($posts, Response::HTTP_OK);
    }
}
