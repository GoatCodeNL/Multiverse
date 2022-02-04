<?php

namespace App\Controller;

use App\RickMortyClient\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{
    private Client $rickMortyClient;

    public function __construct(Client $rickMortyClient)
    {
        $this->rickMortyClient = $rickMortyClient;
    }

    #[Route('/location', name: 'locationIndex')]
    public function locationIndex(Request $request): Response
    {
        $locationsPerPage = 9;
        $page = $request->get("page") ?? 1;

        return $this->render('location/index.html.twig', [
            'locations' => $this->rickMortyClient->getLocations(($page - 1) * $locationsPerPage, $locationsPerPage),
            'page' => $page,
        ]);
    }

    #[Route('/location/{id}', name: 'locationShow')]
    public function locationShow(int $id): Response
    {
        $location = $this->rickMortyClient->getLocation($id);
        return $this->render('location/show.html.twig', [
            'location' => $location,
            'residents' => $this->rickMortyClient->getCharactersBulk($location->getResidents()),
        ]);
    }
}
