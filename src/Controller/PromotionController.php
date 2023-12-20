<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Form\PromotionFormType;
use App\Repository\ProductRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('promotion/')]
class PromotionController extends AbstractController
{
    #[Route('list', name: 'app_list_promotion')]
    public function list(PromotionRepository $promoRepo): Response
    {
        $promotions = $promoRepo->findAll();

        return $this->render('promotion/list.html.twig', [
            'title' => 'Liste des promotions',
            'promotions' => $promotions,
        ]);
    }

    #[Route('create', name: 'app_create_promotion')]
    public function create(ProductRepository $productRepo, Request $request, EntityManagerInterface $em): Response
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionFormType::class, $promotion, ["products"=>$productRepo->findAll()]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $em->persist($promotion);
            $em->flush();
            return $this->redirectToRoute("app_list_promotion");
        }

        return $this->render('promotion/create.html.twig', [
            'title' => 'Création d\'une nouvelle promotion',
            'form' => $form,
        ]);
    }

    #[Route('display/{id}', name: 'app_display_promotion')]
    public function display(?Promotion $promotion, PromotionRepository $promoRepo, ProductRepository $productRepo, Request $request, EntityManagerInterface $em): Response
    {
        if ($promotion === NULL) {
            $this->addFlash(
                'warning',
                'Promotion non existante'
            );
            $this->redirectToRoute('app_list_promotion');
        }
        $form = $this->createForm(PromotionFormType::class, $promotion, ["products"=>$productRepo->findAll()]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Cascade persist ??????
            $em->persist($promotion);
            $em->flush();
            return $this->redirectToRoute("app_list_promotion");
        }

        return $this->render('promotion/create.html.twig', [
            'title' => 'Promotion',
            'promotion' => $promotion,
            'form' => $form,
        ]);

    }
}
