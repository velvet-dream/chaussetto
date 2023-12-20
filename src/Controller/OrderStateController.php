<?php

namespace App\Controller;

use App\Entity\OrderState;
use App\Repository\OrderStateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class OrderStateController extends AbstractController
{
   
#[Route('/orderstate', name: 'app_state_order')]
public function index(OrderStateRepository $orderRepository, Request $request): Response
{
    // Récupérer la liste des commandes (order) depuis le repository
    //$orders = $orderRepository->findAll();

    // Exemple d'utilisation du repository pour obtenir le statut d'une commande
    $id_commande = $orderRepository->searchById(4);
       $id_commande[0];
    $id_commande=$id_commande[0]->getLabel();
    // var_dump($id_commande[0]->getLabel());
    // var_dump($id_commande);

    //$statut_commande = $orderRepository->getStatutCommande($id_commande);

    return $this->render('order/order_state.html.twig', [
        'title' => 'Statut de la commande',
        'statut_commande' => $id_commande,
    ]);
}
}

