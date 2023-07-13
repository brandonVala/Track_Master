<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LocationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;

class LocationController extends AbstractController
{
    /**
     * @Route("/locations", name="get_locations", methods={"GET"})
     */
    public function getLocations(Request $request, LocationRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        try {
            $page = $request->query->getInt('page', 1);
            $limit = $request->query->getInt('limit', 10);

            $paginator = $repository->paginate($page, $limit); // Implementa la lógica de paginación en el repositorio

            $data = $serializer->normalize($paginator->getItems(), null, ['groups' => 'location']);

            return new JsonResponse([
                'locations' => $data,
                'total_pages' => $paginator->getTotalPages(),
                'current_page' => $paginator->getCurrentPage(),
                'total_items' => $paginator->getTotalItems()
            ]);
        } catch (\Exception $e) {
            // Manejo de excepciones
            return new JsonResponse(['error' => 'Error retrieving locations'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
