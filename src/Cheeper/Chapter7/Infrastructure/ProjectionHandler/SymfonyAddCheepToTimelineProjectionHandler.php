<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\ProjectionHandler;

use Cheeper\Chapter7\Application\Projector\Timeline\AddCheepToTimelineProjection;
use Cheeper\Chapter7\Application\Projector\Timeline\AddCheepToTimelineProjector;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet cheep-projection-to-redis
final class SymfonyAddCheepToTimelineProjectionHandler implements MessageSubscriberInterface
{
    public function __construct(
        private AddCheepToTimelineProjector $projector,
    ) {
    }

    public function handle(AddCheepToTimelineProjection $projection): void
    {
        $this->projector->__invoke($projection);
    }

    public static function getHandledMessages(): iterable
    {
        yield AddCheepToTimelineProjection::class => [
            'method' => 'handle',
            'from_transport' => 'chapter7_async_projections'
        ];
    }
}
//end-snippet