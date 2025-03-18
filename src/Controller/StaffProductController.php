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
use App\Entity\Image;
use App\Repository\ImageRepository;

#[Route('admin')]
class StaffProductController extends AbstractController
{
    #[Route('/listProduct', name: 'app_list_product')]
    public function listProduct(
        ProductRepository $productRepository,
        Request $request,
        Security $security
    ): Response {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }
        $triName = $request->query->get('triName', 'asc');
        $products = $productRepository->searchByName($request->query->get('name', ''), $triName);


        return $this->render('staff_product/list.html.twig', [
            'title' => 'Liste des produits',
            'products' => $products,
            'triName' => $triName,
            'name' => $request->query->get('name', ''),
        ]);
    }

    #[Route('/addProduct', name: 'app_create_product')]
    public function addproduct(Request $request, ProductRepository $productrepo, ImageRepository $imageRepo, Security $security): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }

        $product = new Product(); // Ou récupérez une catégorie existante à éditer

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData(); // Récupérer le fichier image soumis

            // Vérifier si une image a été soumise
            if ($imageFile) {
                // Générer un nom de fichier unique
                $newFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $imageFile->guessExtension();

                // Déplacer le fichier vers le répertoire où vous souhaitez le stocker
                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/images/web/', // Remplacez 'dossier_images' par le nom de votre répertoire
                    $newFilename
                );

                // Enregistrer le nom de l'image dans l'entité Product
                $img = new Image;
                $img->setName($newFilename);
                $imageRepo->save($img);
                $product->addImage($img);
            }

            $productrepo->save($product);
            $this->addFlash("success", "Produit ajouté avec succès !");
            return $this->redirectToRoute("app_list_product");
        }

        return $this->render('staff_product/addproduct.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/showProduct/{id}', name: 'app_show_product')]
    public function showProduct(?Product $product, Security $security): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_login');
        }

        return $this->render('staff_product/show.html.twig', [
            'title' => 'Détails du produit',
            'product' => $product,
        ]);
    }

    #[Route('/updateProduct/{id}', name: 'app_update_product')]
    public function updateProduct(
        Request $request,
        Security $security,
        Product $product,
        ProductRepository $productRepository,
        ImageRepository $imageRepository,
        int $id
    ): Response {

        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        $product = $productRepository->findProductById($id);

        if ($product === null) {
            return $this->redirectToRoute('app_list_product');
        }

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData(); // Récupérer le fichier image soumis

            // Vérifier si une image a été soumise
            if ($imageFile) {
                // Générer un nom de fichier unique
                $newFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $imageFile->guessExtension();

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

        // Ajout du champ pour afficher le nom de l'image actuelle
        $formView = $form->createView();
        $formView->children['image']->vars['attr']['readonly'] = true;

        // Récupération du nom de la première image du produit (s'il y en a)
        $images = $product->getImages();
        $imageName = null;
        if ($images && count($images) > 0) {
            $imageName = $images[0]->getName(); // Récupérer le nom de la première image
        }

        $formView->children['image']->vars['data'] = $imageName; // Assignation du nom de l'image au champ du formulaire

        return $this->render('staff_product/addproduct.html.twig', [
            'title' => 'Mise à jour d\'un produit !',
            'form' => $form->createView(), // Création de la vue du formulaire
            'product' => $product
        ]);
    }

    #[Route('/deleteProduct/{id}', name: 'app_delete_product')]
    public function deleteProduct(int $id, ProductRepository $productRepository, Security $security): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        $product = $productRepository->findProductById($id);

        if (!$product) {
            // Si le produit n'est pas trouvé, tu peux rediriger ou afficher un message d'erreur
            return $this->redirectToRoute('app_list_product');
        }

        $productRepository->remove($product);

        $this->addFlash("success", "Produit supprimé");
        return $this->redirectToRoute('app_list_product');

        // return $this->render('produit/GestionProduit.html.twig', [
        //     'title' => 'Mise à jour d\'un produit !',
        //     'product' => $product
        // ]);
    }
}
