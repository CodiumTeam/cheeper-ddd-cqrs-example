<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Application\Author\Projection;

use Cheeper\Chapter6\Application\Projection;

//snippet count-followers
final class CountFollowersProjection implements Projection
{
    public static function ofAuthor(string $authorId): self
    {
        return new self($authorId);
    }

    private function __construct(
        private string $authorId
    ) {
    }

    public function authorId(): string
    {
        return $this->authorId;
    }
}
//end-snippet
