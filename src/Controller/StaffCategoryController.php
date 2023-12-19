<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use App\Services\FormCategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// #[Route ('admin/')]
class StaffCategoryController extends AbstractController
{
    // #[Route('/staff/category', name: 'app_category')]
    // public function index(): Response
    // {
    //     return $this->render('staff_category/index.html.twig', [
    //         'controller_name' => 'StaffCategoryController',
    //     ]);
    // }
    #[Route ('showCategory/{id}', name: 'app_show_category')]
    public function showCategory(?Category $category) : Response
    {
        return $this->render('staff_category/show.html.twig', [
            'title' => 'Détails de la catégorie',
            'category' => $category,
        ]);
    }
    

    #[Route ('listCategory', name: 'app_list_category')]
    public function listCategory (CategoryRepository $categoryRepository, Request $request) : Response
    {
        $category = $categoryRepository->searchByName($request->query->get('label', ''));

        return $this->render('staff_category/list.html.twig', [
            'title' => 'Liste des catégories',
            'category' => $category,
        ]);
    }

    #[Route ('createCategory', name: 'app_create_category')]
    public function createCategory (
        Request $request, 
        FormCategoryService $formCategoryService,
        Security $security) : Response
    {
        // if (!$security->isGranted('ROLE_ADMIN')){
        //     return $this->redirectToRoute('app_index');
        // }

        $staff = $security->getUser();
        $staff->getRoles();
        $category = new Category;
        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);
        if ($formCategoryService->submitForm($form, $category,$request)){
            return $this->redirectToRoute('app_index');
        }

        return $this->render('staff_category/new.html.twig', [
            'title' => 'Création d\une nouvelle catégorie !',
            'form' => $form,
        ]);
    }

    #[Route ('updateCategory/{id}', name: 'app_update_category')]
    public function updateCategory (
        Request $request, 
        FormCategoryService $formCategoryService,
        Security $security,
        Category $category) : Response
    {
        // if (!$security->isGranted('ROLE_ADMIN')){
        //     return $this->redirectToRoute('app_index');
        // }
        if($category === null){
            return $this->redirectToRoute('app_show_category');
        }

        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);
        if ($formCategoryService->submitForm($form, $category,$request)){
            return $this->redirectToRoute('app_show_category');
        }

        return $this->render('staff_category/new.html.twig', [
            'title' => 'Mise à jour d\'une catégorie !',
            'form' => $form,
        ]);
    }
    #[Route ('deleteCategory', name: 'app_delete_category')]
    public function deleteCategory() // A FAIRE 
    {

    }




}
