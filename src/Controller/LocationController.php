<?php

namespace App\Controller;

use App\RickMortyClient\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function locationIndex(): Response
    {
        return $this->render('location/index.html.twig', [
            'controller_name' => 'TestController',
            'locations' => $this->rickMortyClient->getLocations()
        ]);
    }

    #[Route('/location/{id}', name: 'locationShow')]
    public function locationShow(int $id): Response
    {
        return $this->render('location/show.html.twig', [
            'controller_name' => 'TestController',
            'location' => $this->rickMortyClient->getLocation($id)
        ]);
    }
}
