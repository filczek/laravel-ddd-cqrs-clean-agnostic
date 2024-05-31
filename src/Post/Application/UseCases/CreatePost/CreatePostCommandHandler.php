<?php

declare(strict_types=1);

namespace DDD\Post\Application\UseCases\CreatePost;

use DDD\Post\Domain\Post;
use DDD\Post\Domain\PostRepository;
use DDD\Shared\Application\Command\CommandHandler;
use DDD\Shared\Application\Event\EventBus;

final readonly class CreatePostCommandHandler implements CommandHandler
{
    public function __construct(
        private PostRepository $posts,
        private EventBus $events
    ) {
    }

    public function __invoke(CreatePostCommand $command): void
    {
        $post = Post::create(
            id: $command->id,
            title: $command->title,
            content: $command->content
        );

        $this->posts->create($post);
        $this->events->publishAll($post->recordedEvents());
    }
}
