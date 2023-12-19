<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;



class OrderController extends AbstractController
{
   
    #[Route('/order', name: 'app_order')]
    public function index(Security $security, OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();

        // Vérification si l'utilisateur est connecté
        if (($user = $security->getUser()) === NULL) {
            return $this->redirectToRoute('app_login');
        }

        // Récupération de la liste des commandes du client concerné
        $orders = $orderRepository->findBy(['customer' => $user]);

        return $this->render('order/history.html.twig', [
            'orders' => $orders,
        ]);
        
    }

}
