<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class OrderController extends AbstractController
{
   
    #[Route('/order', name: 'app_order')]
    public function index(OrderRepository $orderRepository): Response
    {
        
        // Récupérer la liste des commandes (order) depuis le repository
        $orders = $orderRepository->findAll();

        return $this->render('order/history.html.twig', [
            //'controller_name' => 'OrderController',
            'orders' => $orders,
        ]);
    }
}
