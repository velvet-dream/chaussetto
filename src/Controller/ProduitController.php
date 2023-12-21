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


    #[Route('/addProduct', name: 'add_product')]
    public function add_product(Request $request, ProductRepository $productRepository, CategoryRepository $categorieRepo, TaxRepository $taxrepo): Response
    {
        $message = '';
        $categorie = $categorieRepo->findAll();
        $taxes_existantes = $taxrepo->findAll();


        if( $request->getMethod() === 'POST'){
            $name = $request->request->get('name');
            $description = $request->request->get('description');
            $poids = $request->request->get('poids');
            $stock = $request->request->get('stock');
            $taxe = $request->request->get('taxe');
            $category = $request->request->get('category');
            $NomTaxe = $request->request->get('Nomtaxe');
            $nomCat = $request->request->get('nomCat');
            $descat = $request->request->get('descriptionCat');
            $active = $request->request->get('isActive');
            $statut = $request->request->get('statut');
            $price = floatval($request->request->get('prix'));
            


            if($name === '' || $description === '' || $taxe === '' || $stock === '' || $poids === ''){
                $message = 'Un ou des champs sont vides, Veuillez remplir tout ces champs';
            }elseif($category === '' && $nomCat === '' ){
                $message = "Vous n\'avez pas selectionné une catégorie ou créer une catégorie";
            }
            
            else{
                $product = new Product();
                
                $product->setName($name);
                $product->setDescription($description);

                $tax = new Tax();

                $tax->setLabel($NomTaxe);
                $tax->setRate($taxe);


                $taxrepo->save($tax);

                $product->setTax($tax);
                
                $product->setWeight($poids);
                $product->setStock($stock);
                
                $product->setPrice($price);
                
                if($category !== ''){ 
                    
                    $cat = $categorieRepo->findOneByLabel($category);                
                    $product->addCategory($cat);
                }else{
                    $cat = new Category;
                    $cat->setLabel($nomCat);

                    $cat->setDescription($descat);
                    $cat->setIsRootCategory(true);
                    $cat->setPositionning(1);
                    if($active === 'Active'){
                        $cat->setActive(true);
                    }else{
                        $cat->setActive(false);
                    }

                    $product->addCategory($cat);
                }


                if($statut === 'Actif'){
                    $product->setActive(true);
                }else{
                    $product->setActive(false);
                }

                



                
                $productRepository->save($product);
                $message = ' Article créé félicitations';

            }


        }
        return $this->render('produit/ajoutProduit.html.twig', [
            'message' => $message,
            'categories' => $categorie,
            'tax' => $taxes_existantes,
        ]);
    }

}
