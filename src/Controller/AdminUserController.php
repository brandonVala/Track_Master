<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function usersList(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user_registration/lista_user.twig', [
            'users' => $users,
        ]);
        
    }
}


