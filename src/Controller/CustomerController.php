<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Form\CustomerFormType;
use App\Services\PasswordHasherService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('customer/')]
class CustomerController extends AbstractController
{
    // Tableau de bord de l'utilisateurice
    #[Route('dashboard', name: 'app_customer_dashboard')]
    public function dashboard( Security $security ): Response
    {
        // Redirection si client est pas connectéx
        if (($user = $security->getUser()) === NULL) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('customer/dashboard.html.twig', [
            'title' => 'Tableau de bord',
            'customer' => $user,
        ]);
    }

    // Pages "informations personnelles"
    #[Route('informations', name: 'app_customer_informations')]
    public function informations( Security $security ): Response
    {
        if (($user = $security->getUser()) === NULL) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('customer/informations.html.twig', [
            'title' => 'Informations personnelles',
            'customer' => $user,
        ]);
    }

    // Inscription utilisateurice
    #[Route('signup', name: 'app_customer_inscription')]
    public function inscription( Request $request, EntityManagerInterface $em, PasswordHasherService $pwdService ): Response
    {
        $subscriber = new Customer();
        $customerForm = $this->createForm( CustomerFormType::class, $subscriber );

        $customerForm->handleRequest($request);
        if ($customerForm->isSubmitted() && $customerForm->isValid()) {
            // On enregistre le nouvel utilisateur avec son mot de passe hashé.
            $em->persist($pwdService->hashUserPassword($subscriber));
            $em->flush();
            return $this->redirectToRoute("app_login");
        }
        return $this->render('customer/signup.html.twig', [
            'title' => 'S\'inscrire',
            'form' => $customerForm,
        ]);    
    }

    // Mise à jour d'infos utilisateurice
    #[Route('update', name: 'app_customer_update')]
    public function updateInfo( 
        Request $request, 
        EntityManagerInterface $em,
        PasswordHasherService $pwdService,
        Security $security
    ): Response
    {
        if (($user = $security->getUser()) === NULL) {
            return $this->redirectToRoute('app_login');
        }
        $customerForm = $this->createForm(CustomerFormType::class, $user, ['user' => $user]);

        $customerForm->handleRequest($request);
        if ($customerForm->isSubmitted() && $customerForm->isValid()) {
            $em->persist($pwdService->hashUserPassword($user));
            $em->flush();
            
            // On envoie un message flash qui indique que l'utilisateurice a réussi sa msie à jour d'informations !
            $this->addFlash(
                'success',
                'Mise à jour réussie !'
            );

            return $this->redirectToRoute("app_customer_informations");
        }
        return $this->render('customer/signup.html.twig', [
            'title' => 'Mettre à jour les informations',
            'form' => $customerForm,
        ]);    
    }

    #[Route('orders', name: 'app_customer_orders')]
    public function showOrders(Security $security, OrderRepository $orderRepository): Response
    {
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
