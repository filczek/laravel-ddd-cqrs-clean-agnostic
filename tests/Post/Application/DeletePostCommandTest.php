<?php

declare(strict_types=1);

namespace Tests\Post\Application;

use DDD\Post\Application\PostModule;
use DDD\Post\Application\UseCases\CreatePost\CreatePostCommand;
use DDD\Post\Application\UseCases\DeletePost\DeletePostCommand;
use DDD\Post\Application\UseCases\FindPostById\FindPostByIdQuery;
use DDD\Post\Domain\Events\PostWasDeleted;
use DDD\Post\Domain\Exceptions\PostNotFound;
use DDD\Post\Domain\ValueObjects\PostId;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\WithInMemoryEvents;

class DeletePostCommandTest extends TestCase
{
    use WithInMemoryEvents;

    private PostModule $posts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setupEventBus();
        $this->posts = new PostModule();
    }

    #[Test]
    public function deletePost(): void
    {
        // Given
        $id = PostId::nextIdentity()->toString();
        $this->posts->create(new CreatePostCommand(id: $id));

        // When
        $this->posts->delete(DeletePostCommand::fromArray(['id' => $id]));

        // Then
        $this->assertEventWasPublishedOnce(PostWasDeleted::class);

        $this->expectException(PostNotFound::class);
        $this->posts->ofId(new FindPostByIdQuery(id: $id));
    }
}
