<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(PostRepository $postRepository)
    {
        $post = $postRepository->findAll();

        return $this->render('main/index.html.twig', [
            'all_posts' => $post
        ]);
    }

}