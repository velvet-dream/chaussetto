<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

#[Route('/cart', name: 'app_cart')]
class CartController extends AbstractController
{
   
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, ProductRepository $productRepository)
    {
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
        $session->set('panier', []);
        dd($data);

        return $this->render('customer/cart/cart.html.twig', compact('data'));
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

    
}
