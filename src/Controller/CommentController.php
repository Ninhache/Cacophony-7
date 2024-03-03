<?php

namespace App\Controller;

use App\Service\CommentService;
use App\Repository\PostRepository;
use App\Form\CommentType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{

    #[Route('/comment/new/{postId}', name: 'comment_new')]
    public function newComment(
        Request $request,
        CommentService $commentService,
        PostRepository $postRepository,
        EntityManagerInterface $entityManager,
        int $postId
    ): Response {
        $post = $postRepository->find($postId);
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
        
            return $this->redirectToRoute('post_show', ['id' => $postId]);
        }

        // Gérer l'erreur de soumission ici ou rediriger vers le formulaire de commentaire
        // Ceci est juste un placeholder. Ajustez selon vos besoins.
        return $this->redirectToRoute('post_show', ['id' => $postId]);
    }

    #[Route('/comment/delete/{commentId}', name: 'comment_delete')]
    public function deleteComment(
        Request $request,
        CommentService $commentService,
        EntityManagerInterface $entityManager,
        int $commentId
    ) : Response {

        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException("User not found -> You must be logged");
        }

        $comment = $commentService->getComment($commentId);

        if (
            !in_array("ROLE_ADMIN", $user->getRoles())
            ||
            !$user->getUserIdentifier() == $comment->getAuthor()->getUsername()
        ) {
            throw $this->createAccessDeniedException();
        }
        $postId = $comment->getPost()->getId();
        $commentService->deleteComment($commentId);

        return $this->redirectToRoute('post_show', ['id' => $postId]);   
    }

}
