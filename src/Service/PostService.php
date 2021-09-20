<?php

namespace App\Service;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;

class PostService implements PostServiceInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function addPost(Post $post): void
    {
        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function updatePost(Post $post): void
    {
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function removePost(Post $post): void
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

}