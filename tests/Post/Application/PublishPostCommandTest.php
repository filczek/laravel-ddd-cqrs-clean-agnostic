<?php

declare(strict_types=1);

namespace Tests\Post\Application;

use DDD\Post\Application\PostModule;
use DDD\Post\Application\UseCases\CreatePost\CreatePostCommand;
use DDD\Post\Application\UseCases\FindPostById\FindPostByIdQuery;
use DDD\Post\Application\UseCases\PublishPost\PublishPostCommand;
use DDD\Post\Domain\Enums\PostState;
use DDD\Post\Domain\Events\PostWasPublished;
use DDD\Post\Domain\Exceptions\PostCannotBePublished;
use DDD\Post\Domain\ValueObjects\PostId;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\WithInMemoryEvents;

class PublishPostCommandTest extends TestCase
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
    public function publish(): void
    {
        // Given
        $id = PostId::nextIdentity()->toString();
        $this->posts
            ->create(new CreatePostCommand(
                id: $id,
                title: "Hello world!",
                content: "Some example content."
            ));
        $this->getEventBus()->clear();

        // When
        $this->posts->publish(PublishPostCommand::fromArray(['id' => $id]));

        // Then
        $post = $this->posts->ofId(new FindPostByIdQuery(id: $id));

        $this->assertSame($id, $post->id);
        $this->assertSame(PostState::Published->value, $post->state);

        $this->assertCountOfDispatchedEvents(1);
        $this->assertEventWasPublishedOnce(PostWasPublished::class);
    }

    #[Test]
    public function publishingAlreadyPublishedPostThrowsException(): void
    {
        // Given
        $id = PostId::nextIdentity()->toString();
        $this->posts
            ->create(new CreatePostCommand(
                id: $id,
                title: "Some title",
                content: "Some content."
            ));
        $this->getEventBus()->clear();
        $this->posts->publish(new PublishPostCommand(id: $id));

        // Then
        $this->expectException(PostCannotBePublished::class);

        // When
        $this->posts->publish(new PublishPostCommand(id: $id));
    }

    #[Test]
    #[DataProvider('examples')]
    public function cannotBePublished(CreatePostCommand $command): void
    {
        // Given
        $this->posts->create($command);
        $this->getEventBus()->clear();

        // Then
        $this->expectException(PostCannotBePublished::class);

        // When
        $this->posts->publish(new PublishPostCommand(id: $command->id));
    }

    public static function examples(): Generator
    {
        yield 'Cannot publish post due to empty title' => [new CreatePostCommand(id: PostId::nextIdentity()->toString(), title: '', content: 'Some content'), false];
        yield 'Cannot publish post due to empty content' => [new CreatePostCommand(id: PostId::nextIdentity()->toString(), title: 'Some title', content: ''), false];
    }
}
