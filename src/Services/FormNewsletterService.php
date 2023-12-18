<?php

namespace App\Services;

use App\Entity\NewsletterSubscribers;
use App\Repository\NewsletterSubscribersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use function PHPSTORM_META\type;

class FormNewsletterService {

  public function __construct(
    private EntityManagerInterface $em,
    private FormFactoryInterface $formFactory,
    private NewsletterSubscribersRepository $nlRepo
  )
  {
    
  }
  
  
  public function submitForm(FormInterface $form, NewsletterSubscribers $nlSub, ?Request $request):bool
  {
    if ($form->isSubmitted() && $form->isValid()) {
      //if($request->request->has())
      $this->em->persist($nlSub);
      $this->em->flush();
      return true;
    }
    return false;
  }

}