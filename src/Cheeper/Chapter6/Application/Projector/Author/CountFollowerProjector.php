<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Application\Projector\Author;

use Cheeper\DomainModel\Author\AuthorId;
use Doctrine\DBAL\Driver\Connection as Database;
use Predis\ClientInterface as Redis;

//snippet projector-count-followers
final class CountFollowerProjector
{
    public function __construct(
        private Redis $redis,
        private Database $database
    ) {
    }

    public function __invoke(CountFollowers $projection): void
    {
        $authorId = AuthorId::fromString($projection->authorId());

        $stmt = $this->database->prepare(
            "SELECT a.author_id as id, a.username as username, COUNT(*) as followers ".
            "FROM authors a, follows f ".
            "WHERE a.author_id = f.to_author_id ".
            "AND a.author_id = :authorId ".
            "GROUP BY id, username"
        );

        $stmt->bindValue('authorId', $authorId->toString());
        $stmt->execute();

        $result = $stmt->fetchAssociative();
        $result['followers'] = (int) $result['followers'];

        $this->redis->set(
            'author_followers_counter_projection:'.$authorId->toString(),
            json_encode($result)
        );
    }
}
//end-snippet
