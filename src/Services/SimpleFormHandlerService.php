<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Un service qui sert à faire la vérification habituelle , le handleRequest et le persist/flush sur un formulaire
 */
class SimpleFormHandlerService {

  public function __construct(
    private EntityManagerInterface $em
  )
  { }
  
  /**
   * Handles the Request $request, checks if $form is submitted and valid,
   * then persists the entity ($form->getData()) into the data base.
   */
  public function handleForm(FormInterface $form, Request $request):bool
  {
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->em->persist($form->getData());
      $this->em->flush();
      return true;
    }
    return false;
  }

}