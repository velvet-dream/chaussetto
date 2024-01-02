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
use App\Controller\ThumbnailController;
 


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


    #[Route('/updateProduct', name: 'app_updateProduct')]
    public function gestionProduct(ProductRepository $product): Response
    {
        $produit = $product->findAll();

        return $this->render('produit/GestionProduit.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/updateProduct', name: 'app_gestionProduct')]
    public function updateProduct(ProductRepository $product): Response
    {
        $categories = $this->getCategories($categoryRepository);
        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);


        
        return $this->render('produit/GestionProduit.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('produit/detail/{id}', name: 'app_detail')]
    public function detail(Product $produit, 
    ProductRepository $productRepo,
    ): Response
    {


        // Récupérez les produits similaires en fonction de la catégorie du produit actuel, par exemple.
        $category = $produit->getCategories()->first(); 
         // Obtenir les produits similaires
        $products = $productRepo->getProductByCategory($category->getLabel());


        return $this->render('produit/detail.html.twig', [
            'produit' => $produit,
            'products' => $products,
            
        ]);

    }

    #[Route('/category/{id}', name: 'app_categorie')]
    public function showCategorie (
    Category $category,
    ProductRepository $productRepo,
    ThumbnailController $thumbnailController
    ): Response
    {
        $products = $productRepo->findLatestActiveProducts();
        $productThumbnails = $thumbnailController->generateProductThumbnails($products);

        return $this->render('produit/categorie.html.twig', [
            'title' => $category->getLabel(),
            'products' => $category->getProducts(),
            'productThumbnails' => $productThumbnails,

        ]);
    }

}
