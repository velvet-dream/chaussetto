<?php 

namespace App\Services;

use App\Entity\Staff;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use App\Repository\StaffRepository;


class FormStaffService
{
    private ?Staff $staff;

    public function __construct(
        private EntityManagerInterface $em,
        private FormFactoryInterface $formFactoryInterface,
        private StaffRepository $staffRepository,)
    {

    }

    public function submitForm(
        FormInterface $formInterface,
        Staff $staff,
        ?Request $request,
        PasswordHasherService $pwdService) :bool 
    {
        if ($formInterface->isSubmitted() && $formInterface->isValid()) {
            //if($request->request->has())
            $this->em->persist($pwdService->hashUserPassword($staff));
            $this->em->flush();
            return true;
          }
          return false;
    }
}