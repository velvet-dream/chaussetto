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

#[Route('admin/')]
class StaffCategoryController extends AbstractController
{
    #[Route('showCategory/{id}', name: 'app_show_category')]
    public function showCategory(?Category $category, Security $security): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('staff_category/show.html.twig', [
            'title' => 'Détails de la catégorie',
            'category' => $category,
        ]);
    }


    #[Route('listCategory', name: 'app_list_category')]
    public function listCategory(CategoryRepository $categoryRepository, Request $request, Security $security): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_dashboard');
        }
        $triName = $request->query->get('triName', 'asc');
        $category = $categoryRepository->searchByName($request->query->get('label', ''), $triName);

        return $this->render('staff_category/list.html.twig', [
            'title' => 'Liste des catégories',
            'category' => $category,
            'triName' => $triName,
            'label' => $request->query->get('label', '')
        ]);
    }

    #[Route('createCategory', name: 'app_create_category')]
    public function createCategory(
        Request $request,
        FormCategoryService $formCategoryService,
        Security $security,
        CategoryRepository $categoryRepository
    ): Response {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        $staff = $security->getUser();
        $staff->getRoles();
        $category = new Category;
        $form = $this->createForm(CategoryFormType::class, $category);

        $categories = $this->getCategories($categoryRepository);

        $form->handleRequest($request);
        if ($formCategoryService->submitForm($form, $category, $request)) {
            return $this->redirectToRoute('app_index');
        }

        return $this->render('staff_category/new.html.twig', [
            'title' => 'Création d\'une nouvelle catégorie !',
            'form' => $form,
            'categories' => $categories,
            'category' => $category

        ]);
    }

    #[Route('updateCategory/{id}', name: 'app_update_category')]
    public function updateCategory(
        Request $request,
        FormCategoryService $formCategoryService,
        Security $security,
        Category $category,
        CategoryRepository $categoryRepository
    ): Response {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        if ($category === null) {
            return $this->redirectToRoute('app_list_category');
        }
        $categories = $this->getCategories($categoryRepository);

        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);
        if ($formCategoryService->submitForm($form, $category, $request)) {
            return $this->redirectToRoute('app_list_category');
        }

        return $this->render('staff_category/new.html.twig', [
            'title' => 'Mise à jour d\'une catégorie !',
            'form' => $form,
            'categories' => $categories,
            'category' => $category->getParentCategory()
        ]);
    }

    // a refacto 
    #[Route('getCategories', name: 'app_get_categories')]
    public function getCategoriesNav(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        // dd($categories);
        $tabEmpty = [];
        foreach ($categories as $category) {
            if ($category->getParentCategory() === null) {
                $tabEmpty[] = $category;
            }
        }
        // dd($tabEmpty); 
        return $this->render('_header/_nav.html.twig', [
            'header_categories' => $tabEmpty
        ]);
    }

    public function getCategories(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        // dd($categories);
        $tabEmpty = [];
        foreach ($categories as $category) {
            if ($category->getParentCategory() === null) {
                $tabEmpty[] = $category;
            }
        }
        // dd($tabEmpty); 
        return $tabEmpty;
    }

    // #[Route ('deleteCategory', name: 'app_delete_category')]
    // public function deleteCategory() // A FAIRE 
    // {

    // }




}
