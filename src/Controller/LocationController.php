<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LocationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class LocationController extends AbstractController
{
    /**
     * @Route("/locations", name="get_locations", methods={"GET"})
     */
    public function getLocations(LocationRepository $repository): JsonResponse
    {
        $locations = $repository->findAll();
        $data = [];

        foreach ($locations as $location) {
            $data[] = [
                'id' => $location->getId(),
                'latitude' => $location->getLatitude(),
                'longitude' => $location->getLongitude(),
                'user' => [
                    'id' => $location->getUser()->getId(),
                    'username' => $location->getUser()->getUsername()
                ]
            ];
        }

        return new JsonResponse(['locations' => $data]);
    }
}
