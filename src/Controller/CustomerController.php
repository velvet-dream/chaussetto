<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('customer/')]
class CustomerController extends AbstractController
{
    #[Route('dashboard', name: 'app_customer_dashboard')]
    public function dashboard( Security $security ): Response
    {
        if (($user = $security->getUser()) === NULL) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('customer/dashboard.html.twig', [
            'title' => 'Tableau de bord',
            'customer' => $user,
        ]);
    }

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

    #[Route('inscription', name: 'app_customer_inscription')]
    public function inscription( Request $request, EntityManagerInterface $em ): Response
    {
        $subscriber = new Customer();
        $customerForm = $this->createForm( CustomerFormType::class, $subscriber );

        $customerForm->handleRequest($request);
        if ($customerForm->isSubmitted() && $customerForm->isValid()) {
            $em->persist($subscriber);
            $em->flush();
            return $this->redirectToRoute("app_login");
        }
        return $this->render('customer/signup.html.twig', [
            'title' => 'S\'inscrire',
            'form' => $customerForm,
        ]);    
    }
}
