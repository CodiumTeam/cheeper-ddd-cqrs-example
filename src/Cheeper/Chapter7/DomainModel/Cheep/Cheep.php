<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\DomainModel\Cheep;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Cheep\CheepDate;
use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\AllChapters\DomainModel\Cheep\CheepMessage;
use Cheeper\AllChapters\DomainModel\Clock;
use Cheeper\Chapter7\DomainModel\TriggerEventsTrait;

class Cheep
{
    use TriggerEventsTrait;

    private function __construct(
        private AuthorId $authorId,
        private CheepId $cheepId,
        private CheepMessage $cheepMessage,
        private CheepDate $cheepDate,
    ) {
        $this->notifyDomainEvent(
            CheepPosted::fromCheep($this)
        );
    }

    public static function compose(AuthorId $authorId, CheepId $cheepId, CheepMessage $cheepMessage): self
    {
        $now = Clock::instance()
            ->now()
            ->setTimezone(new \DateTimeZone('UTC'))
        ;

        $cheepDate = new CheepDate(
            $now->format('Y-m-d H:i:s')
        );

        return new self(
            $authorId,
            $cheepId,
            $cheepMessage,
            $cheepDate
        );
    }

    final public function authorId(): AuthorId
    {
        return $this->authorId;
    }

    final public function cheepId(): CheepId
    {
        return $this->cheepId;
    }

    final public function cheepMessage(): CheepMessage
    {
        return $this->cheepMessage;
    }

    final public function cheepDate(): CheepDate
    {
        return $this->cheepDate;
    }

    final public function recomposeWith(CheepMessage $cheepMessage): void
    {
        $this->cheepMessage = $cheepMessage;
    }
}
