<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         // get the login error if there is one
         $error = $authenticationUtils->getLastAuthenticationError();

         // last username entered by the user
         $email = $authenticationUtils->getLastUsername();

        
        return $this->render('login/index.html.twig', [
            'title' => 'Connexion',
            'email' => $email,
            'error' => $error,
            
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        // return $this->redirectToRoute('app_index', [
        //     'popup' => 'Vous êtes désormais déconnecté.e'
        // ]);
        // return $this->render('login/login.html.twig', [
        //     'controller_name' => 'LoginController',
        // ]);
    }
}
