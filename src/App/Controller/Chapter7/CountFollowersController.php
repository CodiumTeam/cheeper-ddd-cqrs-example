<?php

declare(strict_types=1);

namespace App\Controller\Chapter7;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\Chapter7\Application\Author\Query\CountFollowersQuery;
use Cheeper\Chapter7\Application\QueryBus;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

//snippet follow-counter-controller
final class CountFollowersController extends AbstractController
{
    #[Route(path: "/chapter7/author/{authorId}/followers-counter", methods: ["GET"])]
    public function __invoke(string $authorId, QueryBus $queryBus, SerializerInterface $serializer, Request $request): Response
    {
        $httpCode = Response::HTTP_ACCEPTED;
        $httpContent = [
            'meta' => [],
            'data' => [],
        ];

        $query = null;
        try {
            $query = CountFollowersQuery::ofAuthor($authorId);
            $timeline = $queryBus->query($query);
            $httpContent['data'] = $timeline;
        } catch (AuthorDoesNotExist $e) {
            $httpCode = Response::HTTP_NOT_FOUND;
            $httpContent['data']['message'] = $e->getMessage();
        } catch (InvalidArgumentException $e) {
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $httpContent['data']['message'] = $e->getMessage();
        } finally {
            $httpContent['meta']['message_id'] = $query?->messageId()?->toString();
        }

        return $this->json(
            $httpContent,
            $httpCode
        );
    }
}
//end-snippet
