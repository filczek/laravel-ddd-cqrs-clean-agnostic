<?php

declare(strict_types=1);

namespace Tests\Post\Application;

use DDD\Post\Application\PostModule;
use DDD\Post\Application\UseCases\CreatePost\CreatePostCommand;
use DDD\Post\Application\UseCases\ListPosts\ListPostsQuery;
use DDD\Post\Application\UseCases\PublishPost\PublishPostCommand;
use DDD\Post\Domain\ValueObjects\PostId;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\WithInMemoryEvents;

class ListPostsQueryTest extends TestCase
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
    public function lists(): void
    {
        // Given
        array_map(fn () => $this->createProject(), range(1, 5));
        $published_posts = array_map(fn () => $this->createRandomPostAndPublishIt(), range(1, 10));
        array_map(fn () => $this->createProject(), range(1, 5));

        $page = 1;
        $per_page = 5;

        // When
        $result = $this->posts->paginate(ListPostsQuery::fromArray(['page' => 1, 'per_page' => $per_page]));

        // Then
        $this->assertCount($per_page, $result->data);

        $this->assertSame($page, $result->pagination->page);
        $this->assertSame($per_page, $result->pagination->per_page);
        $this->assertSame(count($published_posts), $result->pagination->total_items);
        $this->assertSame(count($published_posts) / $per_page, $result->pagination->total_pages);
    }

    public function createProject(): PostId
    {
        $id = PostId::nextIdentity();

        $command = new CreatePostCommand(
            id: $id->toString(),
            title: "Title",
            content: "Content"
        );

        $this->posts->create($command);

        return $id;
    }

    public function createRandomPostAndPublishIt(): PostId
    {
        $id = $this->createProject();

        $this->posts->publish(new PublishPostCommand(id: $id->toString()));

        return $id;
    }

}
