<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Follow;

use Cheeper\DomainModel\Author\AuthorId;

//snippet follows
interface Follows
{
    public function numberOfFollowersFor(AuthorId $authorId): int;
    public function save(Follow $follow): void;
    public function ofFromAuthorIdAndToAuthorId(AuthorId $fromAuthorId, AuthorId $toAuthorId): ?Follow;
}
//end-snippet