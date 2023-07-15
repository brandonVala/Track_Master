<?php

namespace App\Controller;

use App\Entity\Geofence;
use App\Form\GeofenceType;
use App\Repository\GeofenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GeofenceController extends AbstractController
{
    /**
     * @Route("/geofence", name="geofence", methods={"GET"})
     */
    public function index(GeofenceRepository $geofenceRepository): Response
    {
        // Obtener todos los geofences
        $geofence = $geofenceRepository->findAll();

        return $this->render('geofence/index.html.twig', [
            'geofence' => $geofence,
        ]);
    }

    /**
     * @Route("/geofences/new", name="geofence_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $geofence = new Geofence();
        $form = $this->createForm(GeofenceType::class, $geofence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Establecer el usuario y el administrador asociados al geofence
            $geofence->setUser($this->getUser());
            $geofence->setAdmin($this->getUser()->getAdmin());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($geofence);
            $entityManager->flush();

            return $this->redirectToRoute('geofence');
        }

        return $this->render('geofence/new.html.twig', [
            'geofence' => $geofence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/geofences/{id}", name="geofence_show", methods={"GET"})
     */
    public function show(Geofence $geofence): Response
    {
        return $this->render('geofence/show.html.twig', [
            'geofence' => $geofence,
        ]);
    }

    /**
     * @Route("/geofence/{id}/edit", name="geofence_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Geofence $geofence): Response
    {
        $form = $this->createForm(GeofenceType::class, $geofence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('geofence_index');
        }

        return $this->render('geofence/edit.html.twig', [
            'geofence' => $geofence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/geofence/{id}", name="geofence_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Geofence $geofence): Response
    {
        if ($this->isCsrfTokenValid('delete'.$geofence->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($geofence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('geofence_index');
    }

    /**
     * @Route("/geofence/user/{userId}", name="geofence_list_by_user", methods={"GET"})
     */
    public function listByUser(GeofenceRepository $geofenceRepository, $userId): Response
    {
        // Obtener los geofences filtrados por usuario
        $geofences = $geofenceRepository->findBy(['user' => $userId]);

        return $this->render('geofence/index.html.twig', [
            'geofences' => $geofences,
        ]);
    }

    /**
     * @Route("/geofence/admin/{adminId}", name="geofence_list_by_admin", methods={"GET"})
     */
    public function listByAdmin(GeofenceRepository $geofenceRepository, $adminId): Response
    {
        // Obtener los geofences filtrados por administrador
        $geofences = $geofenceRepository->findBy(['admin' => $adminId]);

        return $this->render('geofence/index.html.twig', [
            'geofences' => $geofences,
        ]);
    }
}
