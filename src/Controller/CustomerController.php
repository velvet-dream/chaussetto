<?php

namespace App\Controller;

use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('customer/')]
class CustomerController extends AbstractController
{
    #[Route('dashboard', name: 'app_dashboard')]
    public function dashboard( ?Customer $customer ): Response
    {
        return $this->render('customer/dashboard.html.twig', [
            'title' => 'Tableau de bord',
            'customer' => $customer
        ]);
    }
}
