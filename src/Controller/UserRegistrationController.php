<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserRegistrationController extends AbstractController
{
    /**
     * @Route("/user/registration", name="app_user_registration")
     */
    public function registerUser(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                $roles = $form->get('roles')->getData();
                $user->setRoles([$roles]);
                $user->setAdmin($this->getUser());
            } else {
                $this->addFlash('error', 'You do not have permission to register new users.');
                return $this->redirectToRoute('app_homepage');
            }

            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User registered successfully.');
            return $this->redirectToRoute('app_lista_user');
        }

        return $this->render('user_registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin_register", name="app_admin_register")
     */
    public function adminRegister(): Response
    {
        return $this->render('user_registration/admin_register.html.twig');
    }
}
