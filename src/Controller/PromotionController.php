<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Promotion;
use App\Form\PromotionFormType;
use App\Repository\ProductRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/promotion/')]
class PromotionController extends AbstractController
{
    public function __construct (
        private Security $security
    )
    {}

    #[Route('list', name: 'app_list_promotion')]
    public function list(PromotionRepository $promoRepo): Response
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        $promotions = $promoRepo->findAll();

        return $this->render('promotion/list.html.twig', [
            'title' => 'Liste des promotions',
            'promotions' => $promotions,
        ]);
    }

    #[Route('create', name: 'app_create_promotion')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        $promotion = new Promotion();
        $form = $this->createForm(PromotionFormType::class, $promotion);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($promotion);
            foreach($form->getData()->getProducts() as $product){
                $product->setPromotion($promotion);
                $em->persist($product);
            }
            
            $em->flush();
            return $this->redirectToRoute("app_list_promotion");
        }

        return $this->render('promotion/create.html.twig', [
            'title' => 'Création d\'une nouvelle promotion',
            'form' => $form,
        ]);
    }

    #[Route('update/{id}', name: 'app_update_promotion')]
    public function display(?Promotion $promotion, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        if ($promotion === NULL) {
            $this->addFlash(
                'warning',
                'Promotion non existante'
            );
            $this->redirectToRoute('app_list_promotion');
        }
        $form = $this->createForm(PromotionFormType::class, $promotion, ["promotion"=>$promotion, "label"=>"Mettre à jour"]);
        // var_dump($form->getData()->getProducts());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // On persiste la promotion et tous ses produits
            $em->persist($promotion);
            foreach($form->getData()->getProducts() as $product){
                $product->setPromotion($promotion);
                $em->persist($product);
            }
            $em->flush();
            $this->addFlash(
                'success',
                'Promotion mise à jour!'
            );
            return $this->redirectToRoute("app_list_promotion");
        }

        return $this->render('promotion/create.html.twig', [
            'title' => 'Mise à jour de promotion',
            'promotion' => $promotion,
            'form' => $form,
        ]);

    }

    #[Route('delete/{id}', name: 'app_delete_promotion')]
    public function delete(?Promotion $promotion, EntityManagerInterface $em): Response
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        
        if ($promotion === null) {
            $this->addFlash(
                'warning',
                'Promotion non existante'
            );
            return $this->redirectToRoute('app_list_promotion');
        }
        foreach($promotion->getProducts() as $product){
            $product->setPromotion(null);
            $em->persist($product);
        }
        $em->remove($promotion);
        $em->flush();

        $this->addFlash(
            'success',
            'Promotion supprimée avec succès!'
        );
        return $this->redirectToRoute("app_list_promotion");
    }

    #[Route('{id}', name: 'app_show_promotion')]
    public function show(?Promotion $promotion): Response
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        
        if ($promotion === null) {
            $this->addFlash(
                'warning',
                'Promotion non existante'
            );
            return $this->redirectToRoute('app_list_promotion');
        }

        return $this->render('promotion/show.html.twig', [
            'title' => $promotion->getLabel(),
            'promotion' => $promotion,
        ]);
    }

    #[Route('detach-product/{id}', name: 'app_detach_product_from_promotion')]
    public function detach(?Product $product, EntityManagerInterface $em): Response
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }
        
        if ($product === null) {
            $this->addFlash(
                'warning',
                'Produit non existant'
            );
            return $this->redirectToRoute('app_list_promotion');
        }

        $product->setPromotion(null);
        $em->persist($product);
        $em->flush();
        $this->addFlash(
            'success',
            'Produit retiré!'
        );

        return $this->redirectToRoute('app_list_promotion');
    }
}
