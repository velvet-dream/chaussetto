<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\ThumbnailController;
use App\Services\CartService;
use Symfony\Bundle\SecurityBundle\Security;

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
    public function detail(
        Product $produit,
        ProductRepository $productRepo,
    ): Response {

        // Récupérer les produits similaires en fonction de la catégorie du produit actuel, par exemple.
        $category = $produit->getCategories()->first();
        // Obtenir les produits similaires
        $products = $productRepo->getProductByCategory($category->getLabel());


        return $this->render('produit/detail.html.twig', [
            'produit' => $produit,
            'products' => $products,

        ]);
    }

    #[Route('/category/{id}', name: 'app_categorie')]
    public function showCategorie(
        Category $category,
        ProductRepository $productRepo,
        ThumbnailController $thumbnailController,
        Request $request,
        CartService $cartService,
        Security $security,
    ): Response {

        $products = $productRepo->getProductByCategory($category->getLabel());
        $productThumbnails = $thumbnailController->generateProductThumbnails($products);

        if ($request->isMethod('POST') && !$thumbnailController->handleCartRequests($productThumbnails, $request, $cartService, $security)) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('produit/categorie.html.twig', [
            'title' => $category->getLabel(),
            'productThumbnails' => $productThumbnails,

        ]);
    }
}
