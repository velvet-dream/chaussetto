<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Tax;
use App\Entity\Image;
use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Form\ProductFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ImageRepository;
use App\Repository\CategoryRepository;
use App\Repository\TaxRepository;
 


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


    #[Route('produit/detail/{id}', name: 'app_detail')]
    public function detail(Product $produit, ProductRepository $productRepo): Response
    {



        // Récupérez les produits similaires en fonction de la catégorie du produit actuel, par exemple.
        $category = $produit->getCategories()->first(); // Récupérez la première catégorie (à adapter selon votre logique)
        $products = $productRepo->getProductByCategory($category->getLabel()); // Obtenez les produits similaires

        return $this->render('produit/detail.html.twig', [
            'produit' => $produit,
            'products' => $products,
        ]);

    }

    #[Route('/produit/{category}', name: 'app_categorie')]
    public function showCategorie (ProductRepository $productRepo, string $category): Response
    {
        $product= $productRepo->getProductByCategory($category);
        
    

        return $this->render('produit/categorie.html.twig', [
            'produit' => $product,
        ]);

    }

}
