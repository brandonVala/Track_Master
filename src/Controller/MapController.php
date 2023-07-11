<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MapController extends AbstractController
{
    #[Route('/map', name: 'app_map')]
    public function index(Security $security): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $security->getUser();

        // Accede a los objetos relacionados a través del usuario actual
        $geofences = $user->getGeofences();
        $locations = $user->getLocations();
        $notifications = $user->getNotifications();

        return $this->render('map/index.html.twig', [
            'controller_name' => 'MapController',
            'geofences' => $geofences,
            'locations' => $locations,
            'notifications' => $notifications,
        ]);
    }
}
