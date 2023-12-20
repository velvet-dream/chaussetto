<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Repository\ImageRepository;
 


class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function show_product(ProductRepository $product): Response
    {
        $produit = $product->findAll();

        

        return $this->render('produit/index.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/detail/{id}', name: 'app_detail')]
    public function detail(Product $produit): Response
    {





        return $this->render('produit/detail.html.twig', [
            'produit' => $produit,
        ]);

    }
}
