<?php

declare(strict_types=1);

namespace DDD\Post\Application;

use DDD\Post\Application\Dto\PostDto;
use DDD\Post\Application\Dto\PostDtoPaginatedResult;
use DDD\Post\Application\UseCases\CreatePost\CreatePostCommand;
use DDD\Post\Application\UseCases\DeletePost\DeletePostCommand;
use DDD\Post\Application\UseCases\FindPostById\FindPostByIdQuery;
use DDD\Post\Application\UseCases\ListPosts\ListPostsQuery;
use DDD\Post\Application\UseCases\PublishPost\PublishPostCommand;
use DDD\Post\Application\UseCases\UpdatePost\UpdatePostCommand;
use DDD\Post\Domain\Exceptions\PostCannotBePublished;
use DDD\Shared\Application\Module;

final class PostModule extends Module
{
    public function create(CreatePostCommand $command): void
    {
        $this->handle($command);
    }

    public function ofId(FindPostByIdQuery $query): PostDto
    {
        return $this->execute($query);
    }

    public function paginate(ListPostsQuery $query): PostDtoPaginatedResult
    {
        return $this->execute($query);
    }

    public function update(UpdatePostCommand $command): void
    {
        $this->handle($command);
    }

    /**
     * @throws PostCannotBePublished
     */
    public function publish(PublishPostCommand $command): void
    {
        $this->handle($command);
    }

    public function delete(DeletePostCommand $command): void
    {
        $this->handle($command);
    }
}
