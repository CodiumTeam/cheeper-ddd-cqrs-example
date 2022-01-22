<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Application\Command\Author\WithDomainEvents;

use Cheeper\Chapter6\Application\Command\Author\Follow;
use Cheeper\Chapter6\Application\Event\EventBus;
use Cheeper\AllChapters\DomainModel\Author\Author;
use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\Authors;
use Cheeper\AllChapters\DomainModel\Follow\Follows;

//snippet follow-handler-with-event
final class FollowHandler
{
    public function __construct(
        private Authors  $authors,
        private Follows  $follows,
        // leanpub-start-insert
        private EventBus $eventBus
        // leanpub-end-insert
    ) {
    }

    public function __invoke(Follow $command): void
    {
        $fromAuthorId = AuthorId::fromString($command->fromAuthorId());
        $toAuthorId = AuthorId::fromString($command->toAuthorId());

        $fromAuthor = $this->tryToFindTheAuthorOfId($fromAuthorId);
        $toAuthor = $this->tryToFindTheAuthorOfId($toAuthorId);

        $follow = $this->follows->ofFromAuthorIdAndToAuthorId($fromAuthorId, $toAuthorId);
        if (null !== $follow) {
            return;
        }

        $follow = $fromAuthor->followAuthorId($toAuthor->authorId());
        $this->follows->add($follow);

        // leanpub-start-insert
        $this->eventBus->notifyAll($follow->domainEvents());
        // leanpub-end-insert
    }

    private function tryToFindTheAuthorOfId(AuthorId $authorId): Author
    {
        $author = $this->authors->ofId($authorId);
        if (null === $author) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        return $author;
    }
}
// end-snippet
