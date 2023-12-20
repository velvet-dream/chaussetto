<?php

namespace App\Controller;

use App\Entity\Staff;
use App\Form\StaffFormType;
use App\Repository\StaffRepository;
use App\Services\FormStaffService;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\PasswordHasherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils; 

#[Route('admin/')]
class AdminController extends AbstractController
{
    #[Route(path: 'login', name: 'app_admin_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('app_admin_dashboard');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error
        ]);
    }

    #[Route(path: 'logout', name: 'app_admin_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('dashboard', name: 'app_admin_dashboard')]
    public function dashboard() : Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'title' => 'DASHBOARD ADMIN',
        ]);
    }

    #[Route('myprofile/{id}', name: 'app_admin_profile')]
    public function myprofile(?Staff $staff) : Response
    {


        return $this->render('admin/myprofile.html.twig', [
            'title' => 'Mes informations personnelles',
            'staff' => $staff
        ]);
    }
}
