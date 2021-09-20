<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\Type\PostType;
use App\Repository\PostRepository;
use App\Service\FileUploadService;
use App\Service\PostServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{

    private FileUploadService $fileUploadService;
    private PostRepository $postRepository;
    private PostServiceInterface $postService;

    public function __construct(FileUploadService $fileUploadService, PostRepository $postRepository, PostServiceInterface $postService)
    {

        $this->fileUploadService = $fileUploadService;
        $this->postRepository = $postRepository;
        $this->postService = $postService;
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
            $filename = $this->uploadFile($request);
            $post->setImage($filename);
            $this->postService->addPost($post);
            return $this->redirect('/');
        }

        return $this->render('post/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit-post/{id}", name="app_edit_post")
     */
    public function editPost(int $id, Request $request)
    {
        $post = $this->postRepository->find($id);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filename = $this->uploadFile($request);
            if ($filename) {
                $post->setImage($filename);
            }
            $this->postService->updatePost($post);
            return $this->redirect('/');
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete-post/{id}", name="app_delete_post")
     */
    public function deletePost(int $id)
    {
        $post = $this->postRepository->find($id);
        $this->removeFile($post->getImage());
        $this->postService->removePost($post);
        return $this->redirect('/');
    }

    private function uploadFile(Request $request)
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('post')['image'];
        if ($file) {
            $imagesDirectory = $this->getParameter('images_directory');
            return $this->fileUploadService->generateUniqueFilename($file, $imagesDirectory);
        }
        return null;
    }

    private function removeFile(string $filename)
    {
        $pathToImage = $this->getParameter('images_directory') . '/' . $filename;
        unlink($pathToImage);
    }


}