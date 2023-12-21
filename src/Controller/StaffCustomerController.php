<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Entity\Adress;
use App\Repository\AdressRepository;
use App\Entity\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route ('admin/')]
class StaffCustomerController extends AbstractController
{
    #[Route ('listCustomer', name: 'app_list_customer')]
    public function listCustomer(CustomerRepository $customerRepository,
    Request $request, Security $security, AdressRepository $adressRepository)
    {
        if (!$security->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_admin_dashboard');
        }
        $triName = $request->query->get('triName', 'asc');
        $triLastname = $request->query->get('triLastname', 'asc');
        $trimail = $request->query->get('trimail', 'asc');
        $customers = $customerRepository->searchByName($request->query->get('name', ''), $triName, $triLastname, $trimail);
        // $adress = $adressRepository->searchByName($request->query->get('line1', ''));
        // $adress = $adressRepository->getLine1();

        return $this->render('staff_customer/list.html.twig', [
            'title' => 'Liste des utilisateurs',
            'customers' => $customers,

            'triName' => $triName,
            'triLastname' => $triLastname,
            'trimail' => $trimail,

            'name' => $request->query->get('name',''),
            'lastname' => $request->query->get('lastname',''),
            'email' => $request->query->get('email','')
        ]);
    }
}