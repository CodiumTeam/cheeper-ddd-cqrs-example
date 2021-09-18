<?php

declare(strict_types=1);

namespace Architecture\CQRS\Domain;

//snippet post-repository
interface PostRepository
{
    public function save(Post $post): void;
    public function byId(PostId $id): ?Post;
}
//end-snippet
