<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Projector\Author;

//snippet create-followers-counter-projection-projector
final class CreateFollowersCounterProjection
{
    public static function ofAuthor(string $authorId, string $authorUsername): self
    {
        return new self($authorId, $authorUsername);
    }

    private function __construct(
        private string $authorId,
        private string $authorUsername
    ) {
    }

    public function authorId(): string
    {
        return $this->authorId;
    }

    public function authorUsername(): string
    {
        return $this->authorUsername;
    }
}
//end-snippet