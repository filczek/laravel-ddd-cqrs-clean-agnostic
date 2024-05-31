<?php

declare(strict_types=1);

namespace DDD\Post\Application\UseCases\DeletePost;

use DDD\Post\Domain\PostRepository;
use DDD\Post\Domain\ValueObjects\PostId;
use DDD\Shared\Application\Command\CommandHandler;
use DDD\Shared\Application\Event\EventBus;

final class DeletePostCommandHandler implements CommandHandler
{
    public function __construct(
        private PostRepository $posts,
        private EventBus $events
    ) {
    }

    public function __invoke(DeletePostCommand $command): void
    {
        $post = $this->posts->ofId(PostId::from($command->id));

        $post->delete();

        $this->posts->update($post);
        $this->events->publishAll($post->recordedEvents());
    }
}
