<?php

declare(strict_types=1);

namespace App\Controller\Chapter4;

use Cheeper\Application\Command\QueuedCommand;
use CheeperQueuedCommands\PostCheep;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class DelayedPostCheepController extends AbstractController
{
    //snippet delayed-post-cheep-controller
    #[Route("/cheeps", methods: ["POST"])]
    public function __invoke(Request $request, MessageBusInterface $bus): Response
    {
        $authorId = $request->request->get('authorId');
        $message = $request->request->get('message');

        if (!$authorId) {
            throw new InvalidArgumentException("Author ID should be provided");
        }

        if (!$message) {
            throw new InvalidArgumentException("Message should be provided");
        }

        $cheepId = Uuid::uuid4()->toString();

        $command = PostCheep::fromArray([
            'author_id' => (string)$authorId,
            'cheep_id' => $cheepId,
            'message' => (string)$message,
        ]);

        $queuedCommand = new QueuedCommand($command);

        $bus->dispatch($queuedCommand);

        return new Response('', Response::HTTP_ACCEPTED);
    }
    //end-snippet
}
