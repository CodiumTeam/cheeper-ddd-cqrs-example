<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Infrastructure\Application\Query;

use Cheeper\Chapter5\Application\Query\Query;
use Cheeper\Chapter5\Application\Query\QueryBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use function Functional\first;

//snippet symfony-query-bus
final class SymfonyQueryBus implements QueryBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function query(Query $query): mixed
    {
        try {
            $result = $this->messageBus->dispatch($query)->getMessage();
        } catch (HandlerFailedException $exception) {
            throw first($exception->getNestedExceptions());
        }

        return $result;
    }
}
//end-snippet
