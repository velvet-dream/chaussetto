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
        $allOrders = $orderRepository->findBy(['customer' => $user]);

        // Filtrer les commandes en cours (status 2 et 3)
        $currentOrders = array_filter(
            $allOrders,
            fn (Order $order) => $order->getOrderState()->getId() === 2 || $order->getOrderState()->getId() === 3
        );

        // Filtrer les commandes historiques (status 1 et 4)
        $historicalOrders = array_filter(
            $allOrders,
            fn (Order $order) => $order->getOrderState()->getId() === 1 || $order->getOrderState()->getId() === 4
        );

        return $this->render('order/history.html.twig', [
            'currentOrders' => $currentOrders,
            'historicalOrders' => $historicalOrders,
        ]);
        
    }

}
