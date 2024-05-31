<?php

declare(strict_types=1);

namespace Tests\Post\Application;

use DDD\Post\Application\PostModule;
use DDD\Post\Application\UseCases\CreatePost\CreatePostCommand;
use DDD\Post\Application\UseCases\FindPostById\FindPostByIdQuery;
use DDD\Post\Domain\Enums\PostState;
use DDD\Post\Domain\Events\PostWasCreated;
use DDD\Post\Domain\Exceptions\PostAlreadyExists;
use DDD\Post\Domain\ValueObjects\PostId;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\WithInMemoryEvents;

class CreatePostCommandTest extends TestCase
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
    #[DataProvider('examples')]
    public function create(CreatePostCommand $command): void
    {
        // Given

        // When
        $this->posts->create($command);

        // Then
        $post = $this->posts->ofId(
            FindPostByIdQuery::fromArray(['id' => $command->id])
        );

        $this->assertSame($command->id, $post->id);
        $this->assertSame(PostState::Draft->value, $post->state);
        $this->assertSame(is_null($command->title) ? '' : $command->title, $post->title);
        $this->assertSame(is_null($command->content) ? '' : $command->content, $post->content);

        $this->assertEventWasPublishedOnce(PostWasCreated::class);
        $this->assertCountOfDispatchedEvents(1);
    }

    public static function examples(): Generator
    {
        // Happy path
        yield 'Create post with valid command' => [new CreatePostCommand(id: PostId::nextIdentity()->toString(), title: 'Title 1', content: 'Content 1')];
        yield 'Create post with different command data' => [new CreatePostCommand(id: PostId::nextIdentity()->toString(), title: 'Title 2', content: 'Content 2')];

        // Edge cases
        yield 'Create post with empty title' => [new CreatePostCommand(id: PostId::nextIdentity()->toString(), title: '', content: 'Content 5')];
        yield 'Create post with null title' => [new CreatePostCommand(id: PostId::nextIdentity()->toString(), title: null, content: 'Content 6')];
        yield 'Create post with empty content' => [new CreatePostCommand(id: PostId::nextIdentity()->toString(), title: 'Title 7', content: '')];
        yield 'Create post with null content' => [new CreatePostCommand(id: PostId::nextIdentity()->toString(), title: 'Title 8', content: null)];

        // Unicode characters
        yield 'Create post with Unicode characters in title and content' => [new CreatePostCommand(id: PostId::nextIdentity()->toString(), title: 'Title 9 with ünicode', content: 'Content 9 with ünicode')];
        yield 'Create post with Unicode characters in different fields' => [new CreatePostCommand(id: PostId::nextIdentity()->toString(), title: 'Title 10 with ünicode', content: 'Content 10')];
    }

    #[Test]
    public function throwsIfPostAlreadyExists(): void
    {
        // Given
        $command = CreatePostCommand::fromArray(['id' => PostId::nextIdentity()->toString()]);

        // Then
        $this->posts->create($command);

        // When
        $this->expectException(PostAlreadyExists::class);
        $this->posts->create($command);
    }

    #[Test]
    public function throwsOnInvalidId(): void
    {
        // Given
        $command = new CreatePostCommand(id: '', title: 'Title 3', content: 'Content 3');

        // Then
        $this->expectException(InvalidArgumentException::class);

        // When
        $this->posts->create($command);
    }
}
