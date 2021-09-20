<?php

namespace App\Tests\Service;

use App\Entity\Post;
use App\Service\PostService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;


class PostServiceTest extends TestCase
{
    private PostService $postService;
    /**
     * @var EntityManagerInterface|MockObject
     */
    private $entityManager;

    public function testAddPost()
    {
        $post = new Post();
        $this->entityManager->expects(self::once())->method("persist")->with($post);
        $this->entityManager->expects(self::once())->method("flush");
        $this->postService->addPost($post);
    }

    public function testUpdatePost()
    {
        $post = new Post();
        $this->entityManager->expects(self::once())->method("flush");
        $this->postService->updatePost($post);

    }

    public function testRemovePost()
    {
        $post = new Post();
        $this->entityManager->expects(self::once())->method("remove")->with($post);
        $this->entityManager->expects(self::once())->method("flush");
        $this->postService->removePost($post);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->entityManager = $this->mockEntityManager();
        $this->postService = new PostService($this->entityManager);
    }

    private function mockEntityManager()
    {
        return $this->getMockBuilder(EntityManagerInterface::class)->getMock();
    }
}