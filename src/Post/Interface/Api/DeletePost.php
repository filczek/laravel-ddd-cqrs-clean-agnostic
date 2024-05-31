<?php

declare(strict_types=1);

namespace DDD\Post\Interface\Api;

use DDD\Post\Application\PostModule;
use DDD\Post\Application\UseCases\DeletePost\DeletePostCommand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final readonly class DeletePost
{
    public function __construct(
        private PostModule $posts
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $this->posts->delete(
            DeletePostCommand::fromArray($request->route()->parameters())
        );

        return response(null, Response::HTTP_OK);
    }
}
