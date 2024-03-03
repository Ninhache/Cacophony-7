<?php

namespace App\Controller;


use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\PostRepository;

use App\Service\CommentService;
use App\Service\PostService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{

    #[Route('/post/new', name: 'post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, PostService $postService): Response
    {

        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException("User not found -> You must be logged");
        }

        $post = $postService->newPost($user);

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($post);
            $entityManager->flush();
        
            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/post/show/{id}', name: 'post_show')]
    public function show(
        Request $request,
        CommentService $commentService,
        PostRepository $postRepository,
        EntityManagerInterface $entityManager,
        int $id): Response
    {
        $post = $postRepository->find($id);
        if (!$post) {
            return $this->redirectToRoute('post_error');
        }

        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException("User not found -> You must be logged");
        }

        $comment = $commentService->newComment($post, $user);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($comment);
            $entityManager->flush();
        
            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }
    
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'form' => $form
        ]);
    }

    

    #[Route('/post/error', name: 'post_error')]
    public function error(): Response
    {
        return $this->render('post/error.html.twig');
    }
    
}
