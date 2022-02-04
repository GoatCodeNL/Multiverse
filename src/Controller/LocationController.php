<?php

namespace App\Controller;

use App\RickMortyClient\Character\CharacterClient;
use App\RickMortyClient\Location\LocationClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{
    private LocationClient $locationClient;
    private CharacterClient $characterClient;

    public function __construct(LocationClient $locationClient, CharacterClient $characterClient)
    {
        $this->locationClient = $locationClient;
        $this->characterClient = $characterClient;
    }

    #[Route('/location', name: 'locationIndex')]
    public function locationIndex(Request $request): Response
    {
        $locationsPerPage = 9;
        $page = $request->get("page") ?? 1;

        return $this->render('location/index.html.twig', [
            'locations' => $this->locationClient->getAll(($page - 1) * $locationsPerPage, $locationsPerPage),
            'page' => $page,
            'itemsPerPage' => $locationsPerPage,
        ]);
    }

    #[Route('/location/{id}', name: 'locationShow')]
    public function locationShow(int $id): Response
    {
        $location = $this->locationClient->get($id);
        return $this->render('location/show.html.twig', [
            'location' => $location,
            'residents' => $this->characterClient->getBulk($location->getResidents()),
        ]);
    }
}
