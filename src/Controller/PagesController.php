<?php

namespace App\Controller;

use App\Entity\CartLine;
use App\Entity\Contact;
use App\Entity\Customer;
use App\Entity\NewsletterSubscribers;
use App\Form\AddToCartFormType;
use App\Form\ContactFormType;
use App\Form\NewsletterFormType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Services\CartService;
use App\Services\SimpleFormHandlerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\ThumbnailController;

class PagesController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index( ProductRepository $productRepo, 
        Request $request,
        SimpleFormHandlerService $formHandler, 
        Security $security,
        CartService $cartService,
        ThumbnailController $thumbnailController,        
    ): Response
    {
        $products = $productRepo->findLatestActiveProducts();
        $subscriber = new NewsletterSubscribers();
        $cartLine = new CartLine();
        $nlForm = $this->createForm( NewsletterFormType::class, $subscriber );
        $productThumbnails = $thumbnailController->generateProductThumbnails($products);

        // $categories = $this->getCategories($categoryRepository);

        if ($formHandler->handleForm($nlForm, $request)) {
            // On envoie un message flash qui indique que l'utilisateurice a réussi sa msie à jour d'informations !
            $this->addFlash(
                'success',
                'Merci de votre inscription à la Newsletter !'
            );
        } // sinon si on a un POST et que l'utilisateur n'est pas connecté alors qu'il essaie d'ajouter un article...
        elseif ($request->isMethod('POST') && !$thumbnailController->handleCartRequests($productThumbnails, $request, $cartService, $security)) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('pages/index.html.twig', [
            'title' => 'Accueil',
            'newsform' => $nlForm,
            'products' => $products,
            'productThumbnails' => $productThumbnails,
            // 'categories' => $categories
        ]);
    }

    // Nom, email, message, // entité
    #[Route('/contact', name: 'app_contact')]
    public function contact(SimpleFormHandlerService $formHandler, Request $request, Security $security): Response
    {
        $contact = new Contact();
        if (($user = $security->getUser()) !== NULL) {
            $contact->setCustomer($user);
        }
        $contactForm = $this->createForm( ContactFormType::class, $contact, ['customer' => ($user) ? $user : new Customer()] );

        if ($formHandler->handleForm($contactForm, $request)) {
            $this->addFlash('success', 'Votre message a bien été envoyé. Nous vous remercions de votre contribution!');
            return $this->redirectToRoute("app_index");
        }

        return $this->render('pages/contact.html.twig', [
            'title' => 'Nous Contacter',
            'contactForm' => $contactForm,
        ]);
    }

    #[Route('/categories_links/{isFooter}', name: 'app_categories_links')]
    public function getCategories(CategoryRepository $categoryRepository, $isFooter): Response
    {
        $categories = $categoryRepository->findRootCategories();
        $template = ($isFooter) ? "categories_footer_links" : "categories_links";
    
        return $this->render("fragments/$template.html.twig", [
            'categories' => $categories,
        ]);
    }  
    
}
