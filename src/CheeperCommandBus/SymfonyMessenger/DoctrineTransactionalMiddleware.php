<?php

declare(strict_types=1);

namespace CheeperCommandBus\SymfonyMessenger;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

//snippet doctrine-transactional-middleware
final class DoctrineTransactionalMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        return $this->em->transactional(
            static fn (): Envelope => $stack->next()->handle($envelope, $stack)
        );
    }
}
//end-snippet
