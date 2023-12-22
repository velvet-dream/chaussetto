<?php

namespace App\Services;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use App\Repository\CategoryRepository;

class FormCategoryService 
{
    private ?Category $category;

    public function __construct(
        private EntityManagerInterface $em,
        private FormFactoryInterface $formFactoryInterface,
        private CategoryRepository $categoryRepository)
    {
    }

    public function submitForm(FormInterface $formInterface, Category $category, ?Request $request):bool
  {
    if ($formInterface->isSubmitted() && $formInterface->isValid()) {
      // dd($request);
      if ($parentCategory= $request->request->get('parentCategory')){
        // dd($parentCategory);
        $category->setParentCategory($this->categoryRepository->findBy([
          'id' => $parentCategory
        ])[0]);
      } else {
        $category->setParentCategory();
      }
      //if($request->request->has())
      $this->em->persist($category);
      $this->em->flush();

      return true;
    }
    
    return false;
  }
}