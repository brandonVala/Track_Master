<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserRegistrationController extends AbstractController
{
    /**
     * @Route("/user/registration", name="app_user_registration")
     */
    public function registerUser(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserRegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Verificar si el usuario actual tiene el rol de administrador
            if ($this->isGranted('ROLE_ADMIN')) {
                // El usuario actual es un administrador, establecer el rol del nuevo usuario
                $roles = $form->get('roles')->getData();
                $user->setRoles([$roles]);

                // Establecer la relación con el administrador que lo registró
                $user->setAdmin($this->getUser());
            } else {
                // El usuario actual no tiene permiso para registrar usuarios
                $this->addFlash('error', 'You do not have permission to register new users.');
                return $this->redirectToRoute('app_homepage'); // Redirigir a la página principal o a cualquier otra página.
            }

            // Resto del código para persistir el usuario y redireccionar
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User registered successfully.');

            return $this->redirectToRoute('app_homepage'); // Redireccionar a la página principal, o donde desees enviar al usuario después del registro.
        }

        return $this->render('user_registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

