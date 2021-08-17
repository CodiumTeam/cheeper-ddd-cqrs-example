<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Application\Command;

use Cheeper\DomainModel\DomainEvent;

//snippet event-bus
interface EventBus
{
    public function notify(DomainEvent $event): void;
    public function notifyAll(array $domainEvents): void;
}
//end-snippet