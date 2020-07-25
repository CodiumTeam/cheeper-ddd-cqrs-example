<?php

declare(strict_types=1);


namespace Cheeper\Tests\Helper;

use Cheeper\Application\Command\Author\Follow;
use Cheeper\Application\Command\Author\FollowHandler;
use Cheeper\Application\Command\Author\SignUp;
use Cheeper\Application\Command\Author\SignUpHandler;
use Cheeper\Application\Command\Cheep\PostCheep;
use Cheeper\Application\Command\Cheep\PostCheepHandler;
use Cheeper\DomainModel\Author\Authors;
use Cheeper\DomainModel\Cheep\Cheeps;
use Cheeper\Infrastructure\Persistence\InMemoryAuthors;
use Cheeper\Infrastructure\Persistence\InMemoryCheeps;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

trait SendsCommands
{
    private Authors $authors;
    private Cheeps $cheeps;

    /** @before */
    protected function makeUserRepository(): void
    {
        $this->authors = new InMemoryAuthors();
    }

    //snippet setup-cheeps-repository
    /** @before */
    protected function makeCheepsRepository(): void
    {
        $this->cheeps = new InMemoryCheeps();
    }
    //end-snippet

    private function signUpAuthorWith(string $authorId, string $userName, string $email, string $name, string $biography, string $location, string $website, string $birthDate): void
    {
        (new SignUpHandler(
            $this->authors
        ))(
            new SignUp(
                $authorId,
                $userName,
                $email,
                $name,
                $biography,
                $location,
                $website,
                $birthDate
            )
        );
    }

    private function followAuthor(string $followee, string $followed): void
    {
        (new FollowHandler($this->authors))(
            Follow::anAuthor($followed, $followee)
        );
    }

    //snippet post-new-cheep-tests
    private function postNewCheep(string $authorId, string $cheepId, string $message): void
    {
        (new PostCheepHandler($this->authors, $this->cheeps))(
            PostCheep::fromArray([
                'author_id' => $authorId,
                'cheep_id' => $cheepId,
                'message' => $message,
            ])
        );
    }
    //end-snippet
}
