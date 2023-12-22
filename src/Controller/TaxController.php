<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TaxFormType;
use App\Services\SimpleFormHandlerService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

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
}
