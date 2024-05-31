<?php

declare(strict_types=1);

namespace DDD\Post\Interface\Api;

use DDD\Post\Application\PostModule;
use DDD\Post\Application\UseCases\PublishPost\PublishPostCommand;
use DDD\Post\Domain\Exceptions\PostCannotBePublished;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ReflectionClass;

final readonly class PublishPost
{
    public function __construct(
        private PostModule $posts
    ) {
    }

    public function __invoke(Request $request): Response
    {
        try {
            $this->posts->publish(
                PublishPostCommand::fromArray($request->route()->parameters()),
            );

            return response(null, Response::HTTP_OK);
        } catch (PostCannotBePublished $e) {
            $content = [
                'error' => [
                    'code' => (new ReflectionClass($e))->getShortName(),
                    'message' => $e->getMessage(),
                ]
            ];

            return response($content, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
