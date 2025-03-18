<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CartLineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Services\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;


#[Route('/cart')]
class CartController extends AbstractController
{

    #[Route('/', name: 'app_cart_index')]
    public function index(
        Security $security,
        SessionInterface $session,
        ProductRepository $productRepository,
        CartService $cartService
    ) {
        $user = $this->getUser();

        // Vérification si l'utilisateur est connecté
        if (($user = $security->getUser()) === NULL) {
            return $this->redirectToRoute('app_login');
        }

        $cart = $cartService->getUserCart();
        //$cart = $session->get('cart', []);
        // initialisation des variables
        $data = [];
        $total = 0;
        $totalTTC = 0;

        foreach ($cart->getCartLines() as $cl) {
            $product = $cl->getProduct();
            $quantity = $cl->getQuantity();

            $data[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            //le total est égal au prix des produits multiplié par la quantité.
            $total += $product->getPrice() * $quantity;
            $totalTTC += $product->getPrice() * $product->getTaxMultiplier() * $quantity;
        }


        return $this->render('cart/cart.html.twig', compact('data', 'total', 'totalTTC'));
    }

    #[Route('/add/{id}', name: 'app_cart_add')]
    public function add(Product $product, CartService $cartService, CartLineRepository $cartLineRepo)
    {
        //récuperer le produit
        $id = $product->getId();

        //récup le panier s'il y en a un, sinon renvoie de tableau vide
        // $cart = $session->get('cart', []);
        $cart = $cartService->getUserCart();

        if (($existingCL = $cartLineRepo->getCartLine($product, $cart)) === null) {
            $this->addFlash("danger", "Article à ajouter inexistant dans le panier");
        }

        $existingCL->setQuantity($existingCL->getQuantity() + 1);

        $cartService->persistCart($cart);

        // redirection vers page panier
        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/remove/{id}', name: 'app_cart_remove')]
    public function remove(
        Product $product,
        CartService $cartService,
        CartLineRepository $cartLineRepo,
        EntityManagerInterface $em
    ) {
        //récup le panier s'il y en a un.
        // $cart = $session->get('cart', []);
        $cart = $cartService->getUserCart();

        //on retire le produit du panier s'il n'y a qu'un exemplaire
        //sinon on décrémente sa quantité
        if (($existingCL = $cartLineRepo->getCartLine($product, $cart)) === null) {
            $this->addFlash("danger", "Article à retirer inexistant dans le panier");
        }

        if ($existingCL->getQuantity() === 1) {
            $cart->removeCartLine($existingCL);
            $em->remove($existingCL);
        } else {
            $existingCL->setQuantity($existingCL->getQuantity() - 1);
        }


        $cartService->persistCart($cart);

        // redirection vers page panier
        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/delete/{id}', name: 'app_cart_delete')]
    public function delete(
        Product $product,
        CartService $cartService,
        CartLineRepository $cartLineRepo,
        EntityManagerInterface $em
    ) {
        //récup le panier s'il y en a un.
        // $cart = $session->get('cart', []);
        $cart = $cartService->getUserCart();

        // S'il n'y a pas de produit à retirer (cartLine nulle)
        if (($existingCL = $cartLineRepo->getCartLine($product, $cart)) === null) {
            $this->addFlash("danger", "Article à retirer inexistant dans le panier");
        }

        $cart->removeCartLine($existingCL);
        $em->remove($existingCL);

        $cartService->persistCart($cart);

        // redirection vers page panier
        return $this->redirectToRoute('app_cart_index');
    }
}
