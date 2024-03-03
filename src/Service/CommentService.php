<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Post;
use App\Repository\CommentRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class CommentService
{

    public function __construct(
        private CommentRepository $commentRepository,
        private EntityManagerInterface $entityManager
    ) { }

    public function getComment(int $id) : Comment {
        return $this->commentRepository->find($id);
    }

    public function newComment(Post $post, User $user) : Comment {

        $comment = new Comment();
        $comment->setPost($post);
        $comment->setAuthor($user);
        $comment->setCreatedAt(new DateTimeImmutable());

        return $comment;
    }

    public function deleteComment(int $id) {
        $comment = $this->getComment($id);

        if (!$comment) {
            throw new EntityNotFoundException('No entity found for id ' . $id);
        }

        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }


}
