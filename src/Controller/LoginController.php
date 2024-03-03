<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils) : Response {

        if ($this->getUser()) {
            return $this->redirectToRoute('app_main');
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        $username = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'username' => $username,
            'error' => $error
        ]);
    }
}
