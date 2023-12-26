<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\Customer;
use App\Repository\CarrierRepository;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use App\Repository\OrderStateRepository;
use App\Repository\PaymentMethodRepository;
use App\Repository\ProductRepository;
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
        SessionInterface $session, 
        ProductRepository $productRepository, 
        EntityManagerInterface $em,
        CartRepository $cartRepository,
        OrderStateRepository $orderStateRepository,
        PaymentMethodRepository $paymentMethodRepository,
        CarrierRepository $carrierRepository): Response
    {
    
        // Vérification si l'utilisateur est connecté
        if (($user = $security->getUser()) === NULL) 
        {
            return $this->redirectToRoute('app_login');
        }

        $cart = $cartRepository->getLastCart($user);
        

        // si j'ai un panier vide, j'affiche un message
        // ET redirect accueil.
        if($cart === [])
        {
            $this->addFlash('warning', 'votre panier est vide');
            return $this->redirectToRoute('app_login');        
        }

        // le panier n'est pas vide on créé la commande.
        $order = new Order();
 
        //$customerId
        //on remplit la commande
        $order->setCustomer($user);
        //iniqid définit un identifiant unique.
        //$order->getId();

        //récupération des détails de Order 
        $customerName = $user->getName();
        $customerLastName = $user->getLastName();
        // FAUT CORRIGER !!!!!!!!
        $totalPrice = 100;
        $email = $user->getEmail();
        $orderState = $orderStateRepository->find(1);  
        $billingAdress = $user->getAdress();
        $deliveryAdress = $user->getAdress();
        $paymentMethod = $paymentMethodRepository->find(1);
        $carrier = $carrierRepository->find(1);    

        $order->setCustomerName($customerName);
        $order->setCustomerLastName($customerLastName);
        $order->setTotalPrice($totalPrice);
        $order->setEmail($email);
        $order->setOrderState($orderState);
        $order->setCustomer($user);
        $order->setbillingAdress($billingAdress);
        $order->setDeliveryAdress($deliveryAdress);
        $order->setPaymentMethod($paymentMethod);
        $order->setCarrier($carrier);
        $order->setCart($cart);
        

        // on parcourt le panier pour créer les détails de commande
        foreach($cart->getCartLines() as $cartLine){
            $orderLine = new OrderLine();
            //$order = new Order();
           
            //on va chercher le produit
            $product = $cartLine->getProduct();
            $quantity = $cartLine->getQuantity();
            $productPrice = $product->getPrice();

            //on créé le détail de la commande
            $orderLine->setProductName($product->getName());
            $orderLine->setProductPrice($productPrice);
            $orderLine->setQuantity($quantity);

            $order->addOrderLine($orderLine);

        }
        //dd($order);
        //on persiste et flush
        $em->persist($order);
        $em->flush();

        $this->addFlash('success', 'commande créée avec succés');
        return $this->redirectToRoute('app_index');

        
        
        
    }

}
