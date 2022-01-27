<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\Application\Cheep\Event;

use Cheeper\Chapter7\Application\Cheep\Event\CheepPostedEventHandler;
use Cheeper\Chapter7\DomainModel\Cheep\CheepPosted;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-cheep-posted-event-handler
final class SymfonyCheepPostedEventHandler implements MessageSubscriberInterface
{
    public function __construct(
        private CheepPostedEventHandler $eventHandler
    ) {
    }

    public function handle(CheepPosted $event): void
    {
        $this->eventHandler->handle($event);
    }

    public static function getHandledMessages(): iterable
    {
        yield CheepPosted::class => [
            'method' => 'handle',
            'from_transport' => 'chapter7_events',
        ];
    }
}
//end-snippet