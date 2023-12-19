<?php

namespace App\Controller;

use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('customer/')]
class CustomerController extends AbstractController
{
    #[Route('dashboard', name: 'app_dashboard')]
    public function dashboard( Security $security ): Response
    {
        if (($user = $security->getUser()) === NULL) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('customer/dashboard.html.twig', [
            'title' => 'Tableau de bord',
            'customer' => $user
        ]);
    }
}
