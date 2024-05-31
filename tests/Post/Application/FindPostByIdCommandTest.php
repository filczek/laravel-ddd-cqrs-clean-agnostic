<?php

declare(strict_types=1);

namespace Tests\Post\Application;

use DDD\Post\Application\PostModule;
use DDD\Post\Application\UseCases\CreatePost\CreatePostCommand;
use DDD\Post\Application\UseCases\FindPostById\FindPostByIdQuery;
use DDD\Post\Domain\Exceptions\PostNotFound;
use DDD\Post\Domain\ValueObjects\PostId;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\WithInMemoryEvents;

class FindPostByIdCommandTest extends TestCase
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
    public function ofId(): void
    {
        // Given
        $id = PostId::nextIdentity()->toString();
        $this->posts->create(new CreatePostCommand(id: $id));

        // When
        $actual = $this->posts->ofId(new FindPostByIdQuery(id: $id));

        // Then
        $this->assertSame($id, $actual->id);
    }

    #[Test]
    public function throwsWhenNotExists(): void
    {
        // Given
        $id = PostId::nextIdentity()->toString();

        // Then
        $this->expectException(PostNotFound::class);

        // When
        $this->posts->ofId(new FindPostByIdQuery(id: $id));
    }
}
