<?php

namespace Cheeper\AllChapters\Application\Command\Timeline;

use Redis;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

//snippet put-cheep-on-redis-timeline-handler
final class AddCheepToTimelineHandler implements MessageHandlerInterface
{
    public function __construct(
        private Redis $redis,
    ) {
    }

    public function __invoke(AddCheepToTimeline $message): void
    {
        $this->redis->lPush(
            sprintf("timelines_%s", $message->authorId),
            json_encode([
                'cheep_id' => $message->cheepId,
                'cheep_message' => $message->cheepMessage,
                'cheep_date' => $message->cheepDate,
            ], JSON_THROW_ON_ERROR)
        );
    }
}
//end-snippet