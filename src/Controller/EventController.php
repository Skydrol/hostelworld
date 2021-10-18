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
        $allEvents = $this->getDataFromFile('../var/data.json');

        return new JsonResponse([
            'events' => $allEvents
        ], 200);

    }

    /**
     * @Route("/search", name="search")
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $allEvents = $this->getDataFromFile('../var/data.json');

        $term = $request->query->get('term');
        $date = $request->query->get('date');

        $eventsInLocation = $this->findByLocation($allEvents,$term);

        return new JsonResponse([
            'term' => $term,
            'date' => $date,
            'eventsInLocation' => $eventsInLocation,
            'events' => $allEvents
        ], 200);

    }

    public function getDataFromFile(string $path): array
    {
        $jsonFile = file_get_contents($path);
        return json_decode($jsonFile, true);
    }

    public function findByLocation($allEvents,string $locationTerm): array
    {
        $resultArray = [];
        foreach($allEvents as $event){
            if(preg_match("/{$locationTerm}/i", $event['city'])) {
                array_push($resultArray, $event);
            } elseif (preg_match("/{$locationTerm}/i", $event['country'])){
                array_push($resultArray, $event);
            }
        }
        return $resultArray;
    }

    public function filterByDate(){

    }
}
