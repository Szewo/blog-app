<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\Type\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

        return $this->render('main/index.html.twig', [
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

        if($form->isSubmitted() && $form->isValid())
        {
            $post = $form->getData();
            $em = $this->getDoctrine()->getManager();
            /** @var UploadedFile $file */
            $file = $request->files->get('post')['image'];
            if($file) {
                $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
                $file->move($this->getParameter('images_directory'), $filename);
                $post->setImage($filename);
            }
            $em->persist($post);
            $em->flush();

            return $this->redirect('/');
        }

        return $this->render('post/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit-post/{id}", name="app_edit_post")
     */
    public function editPost(Request $request, int $id)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            /** @var UploadedFile $file */
            $file = $request->files->get('post')['image'];
            if($file) {
                $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
                $file->move($this->getParameter('images_directory'), $filename);
                $post->setImage($filename);
            }
            $em->flush();

            return $this->redirect('/');
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/delete-post/{id}", name="app_delete_post")
     */
    public function deletePost(int $id, PostRepository $postRepository)
    {

        $post = $postRepository->findOneBy(['id' => $id]);
        $pathToImage = $this->getParameter('images_directory') . '/' . $post->getImage();
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        unlink($pathToImage);
        $em->flush();

        return $this->redirect('/');

    }
}