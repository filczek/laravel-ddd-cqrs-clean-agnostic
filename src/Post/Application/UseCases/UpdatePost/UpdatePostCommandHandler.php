<?php

declare(strict_types=1);

namespace DDD\Post\Application\UseCases\UpdatePost;

use DDD\Post\Domain\Exceptions\PostUpdateConflict;
use DDD\Post\Domain\Post;
use DDD\Post\Domain\PostRepository;
use DDD\Post\Domain\ValueObjects\PostContent;
use DDD\Post\Domain\ValueObjects\PostId;
use DDD\Post\Domain\ValueObjects\PostTitle;
use DDD\Shared\Application\Command\CommandHandler;
use DDD\Shared\Application\Event\EventBus;
use DDD\Shared\Domain\ValueObjects\Version;

final class UpdatePostCommandHandler implements CommandHandler
{
    public function __construct(
        private PostRepository $posts,
        private EventBus $events
    ) {
    }

    public function __invoke(UpdatePostCommand $command): void
    {
        $post = $this->posts->ofId(PostId::from($command->id));
        $this->throwIfVersionMismatch($post, $command);

        if (is_string($command->title)) {
            $post->changeTitle(PostTitle::from($command->title));
        }

        if (is_string($command->content)) {
            $post->changeContent(PostContent::from($command->content));
        }

        $this->posts->update($post);
        $this->events->publishAll($post->recordedEvents());
    }

    private function throwIfVersionMismatch(Post $post, UpdatePostCommand $command): void
    {
        $version = Version::from($command->version);

        if ($post->version()->equals($version)) {
            return;
        }

        throw PostUpdateConflict::postHasBeenUpdatedByAnotherUser();
    }
}
