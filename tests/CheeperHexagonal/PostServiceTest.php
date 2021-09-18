<?php

namespace CheeperHexagonal;

use Mockery\Adapter\Phpunit\MockeryTestCase as TestCase;

use Mockery as m;

class PostServiceTest extends TestCase
{
    private PostRepository $postRepository;
    private PostService $postService;

    public function setUp(): void
    {
        $this->postRepository = m::mock(PostRepository::class);
        $this->postService = new PostService($this->postRepository);
    }

    /** @test */
    public function itShouldCreatePost(): void
    {
        $this->postRepository->expects('add');

        $this->postService->createPost('A title', 'Some content');
    }
}
