<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProductFormType;
use App\Entity\Product;
use App\Entity\Tax;
use App\Entity\Image;
use App\Entity\Category;
use App\Repository\ImageRepository;
use App\Repository\TaxRepository;
use App\Repository\CategoryRepository;
use App\Services\FormCategoryService;



#[Route ('admin')]
class StaffProductController extends AbstractController
{
    #[Route('/listProduct', name: 'app_list_product')]
    public function listProduct(ProductRepository $productRepository,
    Request $request, Security $security): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_admin_login');
        }
        $triName = $request->query->get('triName', 'asc');
        $product = $productRepository->searchByName($request->query->get('name', ''), $triName);


        return $this->render('staff_product/list.html.twig', [
            'title' => 'Liste des produits',
            'product' => $product,
            'triName' => $triName,
            'name' => $request->query->get('name', ''),
        ]);
    }

    #[Route('/addProduct', name: 'app_create_product')]
    public function addproduct (Request $request, ProductRepository $productrepo, ImageRepository $imageRepo): Response
    {
        $product = new Product(); // Ou récupérez une catégorie existante à éditer

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData(); // Récupérer le fichier image soumis
            
            // Vérifier si une image a été soumise
            if ($imageFile) {
                // Générer un nom de fichier unique
                $newFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME).'.'.$imageFile->guessExtension();
    
                // Déplacer le fichier vers le répertoire où vous souhaitez le stocker
                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/assets/img/web/', // Remplacez 'dossier_images' par le nom de votre répertoire
                    $newFilename
                );
    
                // Enregistrer le nom de l'image dans l'entité Product
                $img = new Image;
                $img->setName($newFilename);
                $imageRepo->save($img);
                $product->addImage($img);
            }

            $productrepo->save($product);

        }

        return $this->render('staff_product/addproduct.html.twig', [
            'form' => $form,
    
    
        ]);

    }

    #[Route('/showProduct/{id}', name : 'app_show_product')]
    public function showProduct (?Product $product, Security $security) : Response
    {
        if (!$security->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_admin_login');
        }

        return $this->render('staff_product/show.html.twig', [
            'title' => 'Détails du produit',
            'product' => $product,
        ]);
    }

    public function gestionProduct(ProductRepository $productRepository)
    {
        $product = $productRepository->findAll();
    }


    #[Route('/updateProduct/{id}', name: 'app_update_product')]
    public function updateProduct (
        Request $request,
        Security $security,
        Product $product,
        ProductRepository $productRepository,
        ImageRepository $imageRepository
    ) : Response
    {
        if (!$security->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_index');
        }

        if ($product === null){
            return $this->redirectToRoute('app_list_product');
        }

        $product = $this->gestionProduct($productRepository);
        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $imageFile = $form->get('image')->getData(); // Récupérer le fichier image soumis
            
            // Vérifier si une image a été soumise
            if ($imageFile) {
                // Générer un nom de fichier unique
                $newFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME).'.'.$imageFile->guessExtension();
    
                // Déplacer le fichier vers le répertoire où vous souhaitez le stocker
                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/assets/img/web/', // Remplacez 'dossier_images' par le nom de votre répertoire
                    $newFilename
                );
    
                // Enregistrer le nom de l'image dans l'entité Product
                $img = new Image;
                $img->setName($newFilename);
                $imageRepository->save($img);
                $product->addImage($img);
            }

            $productRepository->save($product);
        }

        return $this->render('staff_product/addproduct.html.twig', [
            'title' => 'Mise à jour d\'un produit !',
            'form' => $form,
            'product' => $product
        ]);
    }

    #[Route('/deleteProduct/{id}', name: 'app_delete_product')]
    public function deleteProduct(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findProductById($id);

        if (!$product) {
            // Si le produit n'est pas trouvé, tu peux rediriger ou afficher un message d'erreur
            return $this->redirectToRoute('app_list_product');
        }
    
        $productRepository->remove($product);

        return $this->redirectToRoute('app_gestionProduct');

        return $this->render('produit/GestionProduit.html.twig', [
            'title' => 'Mise à jour d\'un produit !',
                'product' => $product
        ]);
    }

    
}
