<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

/**
 * @Route("/api/events")
 */
class EventController extends AbstractController
{




    /**
     * @Route("/all", name="event", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns all events",
     *     @OA\JsonContent(type="array", description="returns all events")
     * )
     * @OA\Tag(name="events")
     * @Security(name="Bearer")
     */
    public function index(): JsonResponse
    {
        $projectRoot = $this->getParameter('kernel.project_dir');
        $allEvents = $this->getDataFromFile($projectRoot.'/data.json');

        return new JsonResponse([
            'events' => $allEvents
        ], 200);

    }

    /**
     * @Route("/search", name="search", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $projectRoot = $this->getParameter('kernel.project_dir');
        $allEvents = $this->getDataFromFile($projectRoot.'/data.json');

        $term = $request->query->get('term');
        $date = $request->query->get('date');

        $eventsFilteredByLocation = $this->findByLocation($allEvents,$term);

        $eventsFilteredByLocationAndDate = $this->filterByDate($eventsFilteredByLocation, $date);

        return new JsonResponse([
            'events' => $eventsFilteredByLocationAndDate,
        ], 200);

    }

    public function getDataFromFile(string $path): array
    {
        $jsonFile = file_get_contents($path);
        return json_decode($jsonFile, true);
    }

    public function findByLocation(?array $allEvents, ?string $locationTerm): array
    {
        if($locationTerm == null || $locationTerm == ''){
            return $allEvents;
        }

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

    public function filterByDate(?array $eventsFilteredByLocation, ?string $date): array
    {
        if($date == null || $date == ''){
            return $eventsFilteredByLocation;
        }

        if($this->validateDate($date)){

            $eventsFilteredByDate = [];

            foreach ($eventsFilteredByLocation as $event){

                $eventStartDate = new DateTime($event['startDate']);
                $eventEndDate = new DateTime($event['endDate']);
                $queryDate = new DateTime($date);

                if($queryDate >= $eventStartDate && $queryDate <= $eventEndDate){
                    array_push($eventsFilteredByDate, $event);
                }
            }

            return $eventsFilteredByDate;
        }

        return [
            'error' => 'The date provided is invalid or is in the past!',
            'date' => $date, 'now' => new DateTime()
        ];

    }

    public function checkIfDateIsNotPast(?string $dateString): bool
    {
        date_default_timezone_set("Europe/Dublin");
        $date = new DateTime($dateString);
        $now = new DateTime();

        if($date >= $now) {
            return true;
        }

        return false;
    }

    public function checkDateString($date, $format = 'Y-m-d'): bool
    {
        $parsedDate = DateTime::createFromFormat($format, $date);
        return $parsedDate && $parsedDate->format($format) === $date;
    }

    public function validateDate($date): bool
    {
        if($this->checkDateString($date) == false){
            return false;
        }

        if($this->checkIfDateIsNotPast($date) == false){
            return false;
        }

        return true;
    }
}
