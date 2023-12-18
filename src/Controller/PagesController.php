<?php

namespace App\Controller;

use App\Entity\NewsletterSubscribers;
use App\Form\NewsletterFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        $subscriber = new NewsletterSubscribers();
        $newsform = $this->createForm( NewsletterFormType::class, $subscriber );
        return $this->render('pages/index.html.twig', [
            'title' => 'Accueil',
            'newsform' => $newsform,
        ]);
    }
}
