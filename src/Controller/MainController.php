<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;
use App\Entity\Post;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{

    

    #[Route('/main', name: 'app_main')]
    public function index(EntityManagerInterface $entityManager): Response {

        $user = $this->getUser();

        // if (!$user) {
        //     return $this->redirectToRoute('app_login');
        // }
        
        $userRepository = $entityManager->getRepository(User::class);
        $users = $userRepository->findAll();

        $postRepository = $entityManager->getRepository(Post::class);
        $posts = $postRepository->findAll();

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'user' => $user,
            'users' => $users,
            'posts' => $posts
        ]);
    }
}
