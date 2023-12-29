<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\CartLine;
use App\Form\AddToCartFormType;
use App\Repository\CartLineRepository;
use App\Repository\CartRepository;
use App\Services\SimpleFormHandlerService;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ce service sert à gérer l'ajout d'un article au panier avec le formulaire.
 * La méthode addToCartHandle fait deux choses :
 * 1- Elle lie la soumission au formulaire (ajouter au panier) au panier actuel de l'utilisateurice
 * 2- Elle appelle elle même le SimpleFormHandlerService pour persister l'ajout au panier
 */

class CartService {

    public function __construct(
        private Security $security,
        private CartRepository $cartRepo,
        private SimpleFormHandlerService $simpleService,
        private EntityManagerInterface $em,
        private CartLineRepository $cartLineRepo
    )
    {
        // Empty
    }

    public function addToCartHandle(FormInterface $form, Request $request): bool
    {
        if (($customer = $this->security->getUser()) === NULL) {
            return false;
        }
        $cartLine = $form->getData();
        $currentCart = $this->getUserCart();
        $cartLine->setCart($currentCart);
        $form->setData($cartLine);
        //return $this->simpleService->handleForm($form, $request);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newCL = $form->getData();
            //dd($newCL);
            if (($existingCL = $this->cartLineRepo->getCartLine($newCL->getProduct(), $newCL->getCart())) !== null) {
                $request->getSession()->getFlashBag()->add(
                    'warning',
                    'Vous avez déjà ce produit dans votre panier : sa quantité a été modifiée.'
                );
                $existingCL->setQuantity($newCL->getQuantity() + $existingCL->getQuantity());
            } else {
                $request->getSession()->getFlashBag()->add(
                    'success',
                    'Article ajouté au panier !'
                );
                $currentCart->addCartLine($form->getData());
            }
            $this->persistCart($currentCart);
            return true;
        } else {
            return false;
        }
    }

    public function persistCart(Cart $cart):void
    {
        $this->em->persist($cart);
        $this->em->flush();
    }

    public function getUserCart(): Cart
    {
        if (($user = $this->security->getUser()) === NULL) {
            throw new Error("Cannot get a cart if user isn't authenticated");
        }
        // Si l'user n'a pas de cart on lui en crée un
        if (($userCart = $this->cartRepo->getLastCart($user)) === NULL) {
            $userCart = new Cart();
            $userCart->setCustomer($user);
        }
        return $userCart;
    }


}