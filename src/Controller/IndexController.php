<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\Type\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(PostRepository $postRepository)
    {
        $post = $postRepository->findAll();

        return $this->render('index.html.twig', [
            'all_posts' => $post
        ]);
    }

    /**
     * @Route("/add-post", name="app_add_post")
     */
    public function addPost(Request $request)
    {

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('app_index');
        }

        return $this->render('post/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}