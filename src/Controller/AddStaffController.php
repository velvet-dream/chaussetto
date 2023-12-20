<?php

namespace App\Controller;

use App\Entity\Staff;
use App\Form\StaffFormType;
use App\Repository\StaffRepository;
use App\Services\FormStaffService;
use App\Services\PasswordHasherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils; 
#[Route('admin/')]
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
        $triName = $request->query->get('triName', 'asc');
        $staff = $staffRepository->searchAdmin($request->query->get('name', ''),$triName);

        return $this->render('admin/list_staff.html.twig', [
            'title' => 'Liste des administrateur',
            'staff' => $staff,
            'triName' => $triName,
            'name' => $request->query->get('name','')
        ]);
    }





    #[Route(path: 'addAdmin', name: 'app_add_admin')]
    public function AddStaff( 
    StaffRepository $staffRepository,
    Security $security,
    FormStaffService $formStaffService,
    Request $request,
    PasswordHasherService $pwd) : Response
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
        if ($formStaffService->submitForm($form, $admin,$request,$pwd)){
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('admin/add_staff.html.twig', [
            'title' => 'Ajout d\'un administrateur',
            'form' => $form,
        ]);
    }


    #[Route ('updateAdmin/{id}', name: 'app_update_admin')]
    public function updateStaff (
        Request $request, 
        FormStaffService $formCategoryService,
        Security $security,
        Staff $staff,
        PasswordHasherService $pwd) : Response
    {
        // if (!$security->isGranted('ROLE_ADMIN')){
        //     return $this->redirectToRoute('app_index');
        // }
        if($staff === null){
            return $this->redirectToRoute('app_show_admin');
        }

        $form = $this->createForm(StaffFormType::class, $staff);

        $form->handleRequest($request);
        if ($formCategoryService->submitForm($form, $staff,$request,$pwd)){
            return $this->redirectToRoute('app_list_admin');
        }

        return $this->render('admin/add_staff.html.twig', [
            'title' => 'Mise à jour d\'un administrateur !',
            'form' => $form,
        ]);
    }

    // #[Route ('deleteAdmin', name: 'app_delete_admin')]
    // public function deleteAdmin() // A FAIRE 
    // {

    // }
}
