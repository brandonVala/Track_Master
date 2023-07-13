<?php
namespace App\Controller;

use App\Entity\Admin;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator): Response
    {
        $admin = new Admin();
        $form = $this->createForm(RegistrationFormType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $admin->setPassword(
                $passwordHasher->hashPassword(
                    $admin,
                    $form->get('plainPassword')->getData()
                )
            );

            $admin->setName($form->get('name')->getData());
            $admin->setPhoneNumber($form->get('phoneNumber')->getData());

            $entityManager->persist($admin);
            $entityManager->flush();

            // Redirect to login page
            $loginUrl = $urlGenerator->generate('app_login');
            return $this->redirect($loginUrl);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
