<?php

namespace App\Controller;

use App\Repository\TaxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TaxFormType;
use App\Services\SimpleFormHandlerService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

#[Route ('admin/')]
class TaxController extends AbstractController
{

    #[Route('/tax', name: 'app_tax')]
    public function index(Request $request,EntityManagerInterface $em, SimpleFormHandlerService $formHandler): Response
    {
        $form = $this->createForm(TaxFormType::class);  
        if($formHandler->handleForm($form, $request)) {
            return $this->redirectToRoute('app_index');
        }      

        return $this->render('tax\index.html.twig', [
            'title' => 'Création d\'une nouvelle taxe !',
            'form' => $form->createView(),
        ]);
    }

    #[Route('listTax', name: 'app_list_tax')]
    public function listTax(TaxRepository $taxRepository,
    Request $request, Security $security) : Response
    {
        if (!$security->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_admin_dashboard');
        }

        $triName = $request->query->get('triName', 'asc');
        $tax = $taxRepository->searchByName($request->query->get('label',''), $triName);
        
        return $this->render('tax/list.html.twig', [
            'title' => 'Liste des taxes',
            'tax' => $tax,
            'triName' => $triName,
            'label' => $request->query->get('label', '')
        ]);
    }
}
