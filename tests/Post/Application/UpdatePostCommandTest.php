<?php

declare(strict_types=1);

namespace Tests\Post\Application;

use DDD\Post\Application\PostModule;
use DDD\Post\Application\UseCases\CreatePost\CreatePostCommand;
use DDD\Post\Application\UseCases\FindPostById\FindPostByIdQuery;
use DDD\Post\Application\UseCases\PublishPost\PublishPostCommand;
use DDD\Post\Application\UseCases\UpdatePost\UpdatePostCommand;
use DDD\Post\Domain\Events\PostContentWasChanged;
use DDD\Post\Domain\Events\PostTitleWasChanged;
use DDD\Post\Domain\Exceptions\PostContentCannotBeChanged;
use DDD\Post\Domain\Exceptions\PostTitleCannotBeChanged;
use DDD\Post\Domain\Exceptions\PostUpdateConflict;
use DDD\Post\Domain\ValueObjects\PostId;
use DDD\Shared\Domain\ValueObjects\Version;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\WithInMemoryEvents;

class UpdatePostCommandTest extends TestCase
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
    public function updateAll(): void
    {
        // Given
        $id = PostId::nextIdentity()->toString();
        $this->posts->create(new CreatePostCommand(id: $id, title: "Title", content: "Content"));
        $before_update = $this->posts->ofId(new FindPostByIdQuery(id: $id));

        // When
        $this->posts->update(new UpdatePostCommand(
            id: $id,
            version: $before_update->version,
            title: "New title",
            content: "New content"
        ));
        $after_update = $this->posts->ofId(new FindPostByIdQuery(id: $id));

        // Then
        $expected_version = Version::from($before_update->version)->next()->next();
        $this->assertTrue($expected_version->equals(Version::from($after_update->version)));

        $this->assertSame("New title", $after_update->title);
        $this->assertSame("New content", $after_update->content);

        $this->assertEventWasPublishedOnce(PostTitleWasChanged::class);
        $this->assertEventWasPublishedOnce(PostContentWasChanged::class);
    }

    #[Test]
    public function nothingChanges(): void
    {
        // Given
        $id = PostId::nextIdentity()->toString();
        $this->posts->create(new CreatePostCommand(id: $id, title: "Title", content: "Content"));
        $before_update = $this->posts->ofId(new FindPostByIdQuery(id: $id));
        $this->getEventBus()->clear();

        // When
        $this->posts->update(UpdatePostCommand::fromArray(['id' => $id, 'version' => $before_update->version]));
        $after_update = $this->posts->ofId(new FindPostByIdQuery(id: $id));

        // Then
        $this->assertSame($before_update->version, $after_update->version);
        $this->assertSame($before_update->title, $after_update->title);
        $this->assertSame($before_update->content, $after_update->content);

        $this->assertCountOfDispatchedEvents(0);
    }

    #[Test]
    public function nothingChangesIfSame(): void
    {
        // Given
        $id = PostId::nextIdentity()->toString();
        $this->posts->create(new CreatePostCommand(id: $id, title: "Title", content: "Content"));
        $before_update = $this->posts->ofId(new FindPostByIdQuery(id: $id));
        $this->getEventBus()->clear();

        // When
        $this->posts->update(new UpdatePostCommand(
            id: $id,
            version: $before_update->version,
            title: "Title",
            content: "Content"
        ));
        $after_update = $this->posts->ofId(new FindPostByIdQuery(id: $id));

        // Then
        $this->assertSame($before_update->version, $after_update->version);
        $this->assertSame($before_update->title, $after_update->title);
        $this->assertSame($before_update->content, $after_update->content);

        $this->assertCountOfDispatchedEvents(0);
    }

    #[Test]
    public function throwsOnVersionMismatch(): void
    {
        // Given
        $id = PostId::nextIdentity()->toString();
        $this->posts->create(new CreatePostCommand(id: $id, title: "Title", content: "Content"));
        $before_update = $this->posts->ofId(new FindPostByIdQuery(id: $id));

        // Then
        $this->expectException(PostUpdateConflict::class);

        // When
        $this->posts->update(new UpdatePostCommand(
            id: $id,
            version: Version::from($before_update->version)->next()->toString(),
        ));
    }

    #[Test]
    public function changesToEmptyTitleIfNotPublished(): void
    {
        // Given
        $id = PostId::nextIdentity()->toString();
        $this->posts->create(new CreatePostCommand(id: $id, title: "Title", content: "Content"));
        $before_update = $this->posts->ofId(new FindPostByIdQuery(id: $id));

        // When
        $this->posts->update(new UpdatePostCommand(
            id: $id,
            version: $before_update->version,
            title: "New title"
        ));
        $after_update = $this->posts->ofId(new FindPostByIdQuery(id: $id));

        // Then
        $expected_version = Version::from($before_update->version)->next();
        $this->assertTrue($expected_version->equals(Version::from($after_update->version)));

        $this->assertSame("New title", $after_update->title);
        $this->assertSame($before_update->content, $after_update->content);

        $this->assertEventWasPublishedOnce(PostTitleWasChanged::class);
        $this->assertEventWasNotPublished(PostContentWasChanged::class);
    }

    #[Test]
    public function changesToEmptyContentIfNotPublished(): void
    {
        // Given
        $id = PostId::nextIdentity()->toString();
        $this->posts->create(new CreatePostCommand(id: $id, title: "Title", content: "Content"));
        $before_update = $this->posts->ofId(new FindPostByIdQuery(id: $id));

        // When
        $this->posts->update(new UpdatePostCommand(
            id: $id,
            version: $before_update->version,
            content: "New content"
        ));
        $after_update = $this->posts->ofId(new FindPostByIdQuery(id: $id));

        // Then
        $expected_version = Version::from($before_update->version)->next();
        $this->assertTrue($expected_version->equals(Version::from($after_update->version)));

        $this->assertSame($before_update->title, $after_update->title);
        $this->assertSame("New content", $after_update->content);

        $this->assertEventWasNotPublished(PostTitleWasChanged::class);
        $this->assertEventWasPublishedOnce(PostContentWasChanged::class);
    }

    #[Test]
    public function throwsOnEmptyTitleIfPublished(): void
    {
        // Given
        $id = PostId::nextIdentity()->toString();
        $this->posts->create(new CreatePostCommand(id: $id, title: "Title", content: "Content"));
        $this->posts->publish(new PublishPostCommand(id: $id));
        $before_update = $this->posts->ofId(new FindPostByIdQuery(id: $id));

        // Then
        $this->expectException(PostTitleCannotBeChanged::class);

        // When
        $this->posts->update(new UpdatePostCommand(
            id: $id,
            version: $before_update->version,
            title: ""
        ));
    }

    #[Test]
    public function throwsOnEmptyContentIfPublished(): void
    {
        // Given
        $id = PostId::nextIdentity()->toString();
        $this->posts->create(new CreatePostCommand(id: $id, title: "Title", content: "Content"));
        $this->posts->publish(new PublishPostCommand(id: $id));
        $before_update = $this->posts->ofId(new FindPostByIdQuery(id: $id));

        // Then
        $this->expectException(PostContentCannotBeChanged::class);

        // When
        $this->posts->update(new UpdatePostCommand(
            id: $id,
            version: $before_update->version,
            content: ""
        ));
    }
}
