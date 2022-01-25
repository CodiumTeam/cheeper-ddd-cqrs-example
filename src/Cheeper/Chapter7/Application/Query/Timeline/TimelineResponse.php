<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Query\Timeline;

final class TimelineResponse
{
    public function __construct(
        public array $cheeps
    ) {
    }
}