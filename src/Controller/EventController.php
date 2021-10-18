<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/events")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/all", name="event")
     */
    public function index(): JsonResponse
    {
        $jsonData = $this->getDataFromFile('../var/data.json');

        return new JsonResponse([
            'events' => $jsonData
        ], 200);

    }

    /**
     * @Route("/search", name="search")
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $jsonData = $this->getDataFromFile('../var/data.json');

        $term = $request->query->get('term');
        $date = $request->query->get('date');

        return new JsonResponse([
            'term' => $term,
            'date' => $date,
            'events' => $jsonData
        ], 200);

    }

    public function getDataFromFile(string $path)
    {
        $jsonFile = file_get_contents($path);
        return json_decode($jsonFile, true);
    }
}
