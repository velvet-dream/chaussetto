<?php

namespace App\Controller;

use App\Entity\Staff;
use App\Form\StaffFormType;
use App\Repository\StaffRepository;
use App\Services\FormStaffService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils; 

class AddStaffController extends AbstractController
{
    #[Route ('showAdmin/{id}', name: 'app_show_admin')]
    public function showStaff(?Staff $staff) : Response
    {
        return $this->render('admin/show_staff.html.twig', [
            'title' => 'Détails de l\'administrateur',
            'staff' => $staff,
        ]);
    }


    #[Route(path: 'listAdmin', name: 'app_list_admin')]
    public function listStaff(
        StaffRepository $staffRepository,
        Request $request) : Response
    {
        $staff = $staffRepository->searchAdmin($request->query->get('name', ''));

        return $this->render('admin/list_staff.html.twig', [
            'title' => 'Liste des administrateur',
            'staff' => $staff,
        ]);
    }

    #[Route(path: 'addAdmin', name: 'app_add_admin')]
    public function AddStaff( 
    StaffRepository $staffRepository,
    Security $security,
    FormStaffService $formStaffService,
    Request $request) : Response
    {
        // if (!$security->isGranted('ROLE_ADMIN')){
        //     return $this->redirectToRoute('app_index');
        // }
        $staff = $security->getUser();
        $staff->getRoles();
        // var_dump($staff);
        $admin = new Staff;
        // $staffRepository->InsertAdmin($admin);
        $form = $this->createForm(StaffFormType::class, $admin);
        $form->handleRequest($request);
        if ($formStaffService->submitForm($form, $admin,$request)){
            return $this->redirectToRoute('app_index');
        }

        return $this->render('admin/add_staff.html.twig', [
            'title' => 'Ajout d\'un administrateur',
            'form' => $form,
        ]);
    }
}
