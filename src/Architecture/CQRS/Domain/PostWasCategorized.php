<?php

declare(strict_types=1);

namespace Architecture\CQRS\Domain;

final class PostWasCategorized extends DomainEvent
{
    private PostId $postId;
    private CategoryId $categoryId;

    public function __construct(PostId $postId, CategoryId $categoryId)
    {
        $this->postId = $postId;
        $this->categoryId = $categoryId;
    }

    public function postId(): PostId
    {
        return $this->postId;
    }

    public function categoryId(): CategoryId
    {
        return $this->categoryId;
    }
}
