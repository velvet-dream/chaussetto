<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Tax;
use App\Entity\Category;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
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


    #[Route('/addProduct', name: 'add_product')]
    public function add_product(Request $request, ProductRepository $productRepository): Response
    {
        $message = '';
        if( $request->getMethod() === 'POST'){
            $name = $request->request->get('name');
            $description = $request->request->get('description');
            $poids = $request->request->get('poids');
            $stock = $request->request->get('stock');
            $taxe = $request->request->get('taxe');
            $category = $request->request->get('category');
            $NomTaxe = $request->request->get('Nomtaxe');

            if($name === '' || $description === '' || $taxe === '' || $stock === '' || $poids === '' || $category === ''){
                $message = 'Un des champs sont videds, Veuillez remplir tout ces champs';
            } else{
                $product = new Product();
                $product->setName($name);
                $product->setDescription($description);

                $tax = new Tax();

                $tax->setLabel($NomTaxe);
                $tax->setRate($taxe);

                $product->setTax($tax);
                
                $product->setWeight($poids);
                $product->setStock($stock);
                
                $product->addCategory($category);
                
                $productRepository->save($product);
                $message = ' Article créé félicitations';

            }


        }
        return $this->render('produit/ajoutProduit.html.twig', [
            'message' => $message,
        ]);
    }

}
