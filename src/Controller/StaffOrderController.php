<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/order/')]
class StaffOrderController extends AbstractController
{
    #[Route('list', name: 'app_list_orders')]
    public function list(OrderRepository $orderRepo): Response
    {
        $orders = $orderRepo->findAll();
        return $this->render('staff_order/list.html.twig', [
            'title' => 'Liste des commandes',
            'orders' => $orders,
        ]);
    }

    #[Route('{id}', name: 'app_show_order')]
    public function show(?Order $order): Response
    {
        return $this->render('staff_order/show.html.twig', [
            'title' => 'Liste des commandes',
            'order' => $order,
        ]);
    }

}
