<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Bundle\SecurityBundle\Security;


#[Route('/cart', name: 'app_cart')]
class CartController extends AbstractController
{
   
    #[Route('/', name: 'index')]
    public function index(Security $security, SessionInterface $session, ProductRepository $productRepository)
    {
        $user = $this->getUser();

        // Vérification si l'utilisateur est connecté
        if (($user = $security->getUser()) === NULL) {
            return $this->redirectToRoute('app_login');
        }

        $cart = $session->get('cart', []);
       
       // initialisation des variables
       $data = [];
       $total = 0;

        foreach($cart as $id => $quantity){
            $product = $productRepository->find($id);

            $data[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            //le total est égal au prix des produits multiplié par la quantité.
            $total += $product->getPrice() * $quantity;
        }
        

        return $this->render('cart/cart.html.twig', compact('data', 'total'));
    } 

    #[Route('/add/{id}', name: 'app_cart_add')]
    public function add(Product $product, SessionInterface $session)
    {
        //récuperer le produit
        $id = $product->getId();

        //récup le panier s'il y en a un, sinon renvoie de tableau vide
        $cart = $session->get('cart', []);

        //ajout de produit dans la session s'il n'y est pas encore
        //sinon on incrémente sa quantité
        if(empty($cart[$id]))
        {
            $cart[$id] = 3;
        } else {
            $cart[$id]++;
        }

        $session->set('cart', $cart);
       // redirection vers page panier
       return $this->redirectToRoute('app_cart_index');

    }

    #[Route('/remove/{id}', name: 'app_cart_remove')]
    public function remove(Product $product, SessionInterface $session)
    {
        //récuperer le produit
        $id = $product->getId();

        //récup le panier s'il y en a un.
        $cart = $session->get('cart', []);

        //on retire le produit du panier s'il n'y a qu'un exemplaire
        //sinon on décrémente sa quantité
        if(!empty($cart[$id]))
        {
            if($cart[$id] > 1){
                $cart[$id]--;
            } else {
            unset($cart[$id]);
            }
        }

        $session->set('cart', $cart);
       // redirection vers page panier
       return $this->redirectToRoute('app_cart_index');

    }

    #[Route('/delete/{id}', name: 'app_cart_delete')]
    public function delete(Product $product, SessionInterface $session)
    {
        //récuperer le produit
        $id = $product->getId();

        //récup le panier s'il y en a un.
        $cart = $session->get('cart', []);

        //si le panier n'est pas vide
        if(!empty($cart[$id]))
        {
            unset($cart[$id]);
        }

        $session->set('cart', $cart);
        
       // redirection vers page panier
       return $this->redirectToRoute('app_cart_index');

    }

    
}
