<?php

namespace App\Service;

use App\Entity\Post;

interface PostServiceInterface
{
    /**
     * Adds the post.
     *
     * @param Post $post The post to be added.
     */
    public function addPost(Post $post): void;

    /**
     * Updates the post.
     *
     * @param Post $post The post to be updated.
     */
    public function updatePost(Post $post): void;

    /**
     * Removes the post.
     *
     * @param Post $post The post to be removed.
     */
    public function removePost(Post $post): void;

}