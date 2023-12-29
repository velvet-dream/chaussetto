<?php

namespace App\Controller;

use App\Entity\CartLine;
use App\Form\AddToCartFormType;
use App\Services\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('private/')]
class ThumbnailController extends AbstractController {
    /**
     * Méthode qui génère et retourne un array à partir d'une collection de produits contenant :
     * [
     *  "product" => le produit
     *  "cartForm" => le formulaire d'ajout au panier
     *  "formView" => la vue du formulaire (À UTILISER DANS LE {{ form() }} DU TEMPLATE .twig)
     * ]
     * Depuis le controller, écrire : 
     *  $productThumbnails = $cartService->generateProductThumbnail($products, $this);
     * Puis envoyer ce tableau dans le render.
     */
    public function generateProductThumbnails(
        array $products,
        CartLine $cartLine = new CartLine(),
    ): array
    {
        $productThumbnails = [];
        foreach($products as $key=>$product) {
            $productThumbnails[] = [
                "product" => $product,
                "cartForm" => $this->createForm(AddToCartFormType::class, $cartLine),
                "formView" => null,
            ];
            $productThumbnails[$key]["formView"] = $productThumbnails[$key]["cartForm"]->createView();
        }
        return $productThumbnails;
    }

    /**
     * Méthode qui boucle sur tous les formulaires d'un tableau généré par generateProductThumbnails
     * Renvoie true si un formulaire a bien été entré et enregistré ou si aucun formulaire n'a été rentré
     * Renvoie false si la personne essaie de mettre un produit au panier alors qu'elle n'est pas co.
     */
    #[Route('handle', name: 'app_handler')]
    public function handleCartRequests(
        array $productThumbnails,
        Request $request,
        CartService $cartService,
        Security $security,
    ):bool
    {
        foreach($productThumbnails as $productThumbnail) {
            if ($cartService->addToCartHandle($productThumbnail["cartForm"], $request)) {
                return true;
            }
            //Sinon, si le formulaire a été entré sans qu'on soit connecté :
            $productThumbnail["cartForm"]->handleRequest($request);
            if ($productThumbnail["cartForm"]->isSubmitted() && $security->getUser() === NULL) {
                $this->addFlash(
                    'warning',
                    'Veuillez vous connecter pour effectuer vos achats'
                );
                return false;
            }
        }
        return true;
    }

}