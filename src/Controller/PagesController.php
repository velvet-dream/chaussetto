<?php

namespace App\Controller;

use App\Entity\NewsletterSubscribers;
use App\Form\NewsletterFormType;
use App\Repository\NewsletterSubscribersRepository;
use App\Services\FormNewsletterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index( NewsletterSubscribersRepository $nlRepo, FormNewsletterService $formNlService, Request $request): Response
    {
        $subscriber = new NewsletterSubscribers();
        $nlForm = $this->createForm( NewsletterFormType::class, $subscriber );

        $nlForm->handleRequest($request);
        if ($formNlService->submitForm($nlForm, $subscriber, $request)) {
            $popup = "Merci de votre inscription à la Newsletter";
        }

        return $this->render('pages/index.html.twig', [
            'title' => 'Accueil',
            'newsform' => $nlForm,
            'popup' => isset($popup) ? $popup : null,
        ]);
    }
}
