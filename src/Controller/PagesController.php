<?php

namespace App\Controller;

use App\Entity\NewsletterSubscribers;
use App\Form\NewsletterFormType;
use App\Repository\ProductRepository;
use App\Services\FormNewsletterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index( ProductRepository $productRepo, FormNewsletterService $formNlService, Request $request): Response
    {
        $products = $productRepo->findAll();
        $subscriber = new NewsletterSubscribers();
        $nlForm = $this->createForm( NewsletterFormType::class, $subscriber );

        $nlForm->handleRequest($request);
        if ($formNlService->submitForm($nlForm, $subscriber, $request)) {
            // On envoie un message flash qui indique que l'utilisateurice a réussi sa msie à jour d'informations !
            $this->addFlash(
                'success',
                'Merci de votre inscription à la Newsletter'
            );
        }

        return $this->render('pages/index.html.twig', [
            'title' => 'Accueil',
            'newsform' => $nlForm,
            'products' => $products,
        ]);
    }

    
}
