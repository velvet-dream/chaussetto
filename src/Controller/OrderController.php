<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\Customer;
use App\Repository\CarrierRepository;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use App\Repository\OrderStateRepository;
use App\Repository\PaymentMethodRepository;
use App\Repository\ProductRepository;
use App\Services\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



class OrderController extends AbstractController
{
   
    #[Route('/order', name: 'app_order')]
    public function index(Security $security, 
    OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();

        // Vérification si l'utilisateur est connecté
        if (($user = $security->getUser()) === NULL) {
            return $this->redirectToRoute('app_login');
        }

        // Récupération de la liste des commandes du client concerné
        $allOrders = $orderRepository->findBy(['customer' => $user]);

        // TRouver les commandes en cours (status 2 [en cours de livraison] et 3[expédiée])
        $currentOrders = array_filter(
            $allOrders,
            fn (Order $order) => $order->getOrderState()->getId() === 2 || $order->getOrderState()->getId() === 3
        );

        // Filtrer les commandes historiques (status 1 [Livrée] et 4 [annulée])
        $historicalOrders = array_filter(
            $allOrders,
            fn (Order $order) => $order->getOrderState()->getId() === 1 || $order->getOrderState()->getId() === 4
        );

        return $this->render('order/history.html.twig', [
            'currentOrders' => $currentOrders,
            'historicalOrders' => $historicalOrders,
        ]);
        
    }

    #[Route('/ajout', name: 'app_add_order')]
    public function add(Security $security, 
        EntityManagerInterface $em,
        OrderStateRepository $orderStateRepository,
        PaymentMethodRepository $paymentMethodRepository,
        CarrierRepository $carrierRepository,
        CartService $cartService
    ): Response
    {
    
        // Vérification si l'utilisateur est connecté
        if (($user = $security->getUser()) === NULL) 
        {
            return $this->redirectToRoute('app_login');
        }

        $cart = $cartService->getUserCart();
        

        // si j'ai un panier vide, j'affiche un message
        // ET redirect accueil.
        // if($cart === [])
        // {
        //     $this->addFlash('warning', 'Votre panier est vide');
        //     return $this->redirectToRoute('app_login');        
        // }

        // le panier n'est pas vide on créé la commande.
        $order = new Order();
 
        //$customerId
        //on remplit la commande
        $order->setCustomer($user);
        $order->setCustomerName($user->getName());
        $order->setCustomerLastName($user->getLastName());
        $order->setTotalPrice($cart->getTotalPrice());
        $order->setEmail($user->getEmail());
        $order->setOrderState($orderStateRepository->find(1));
        $order->setbillingAdress($user->getAdress());
        $order->setDeliveryAdress($user->getAdress());
        $order->setPaymentMethod($paymentMethodRepository->find(1));
        $order->setCarrier($carrierRepository->find(1));
        $order->setCart($cart);

        // On crée un nouveau panier vide pour notre customer
        $newCart = new Cart();
        $newCart->setCustomer($user);

        // on parcourt le panier pour créer les détails de commande
        foreach($cart->getCartLines() as $cartLine){
            $orderLine = new OrderLine();

            //on va chercher le produit
            $product = $cartLine->getProduct();
            $quantity = $cartLine->getQuantity();
            $productPrice = $product->getPrice();

            //on créé le détail de la commande
            $orderLine->setProductName($product->getName());
            $orderLine->setProductPrice($productPrice);
            $orderLine->setQuantity($quantity);
            $orderLine->setTax($product->getTax()->getRate());
            $orderLine->setTotalPriceVAT($cartLine->getTotalPrice());
            $orderLine->setPromotion($product->getPromotion());

            $order->addOrderLine($orderLine);
        }
        //dd($order);
        //on persiste et flush
        $em->persist($order);
        $cartService->persistCart($newCart);

        $this->addFlash('success', 'Commande créée avec succés !');
        // On redirige où après ???
        return $this->redirectToRoute('app_index');
        
    }

    #[Route('/show/{id}', name: 'app_order_show')]
    public function show(
        Security $security,
        ?Order $order,    
    ): Response

    {
        // Vérification si l'utilisateur est connecté
        if (($user = $security->getUser()) === NULL) 
        {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('order/show.html.twig', [
            'title' => 'TA commande !',
            'order' => $order,     
        ]);
    }

}
