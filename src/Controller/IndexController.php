<?php

namespace App\Controller;

use App\RickMortyClient\Location\LocationClient;
use App\RickMortyIpsumClient\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private LocationClient $locationClient;
    private Client $ipsumClient;

    public function __construct(LocationClient $locationClient, Client $ipsumClient)
    {
        $this->locationClient = $locationClient;
        $this->ipsumClient = $ipsumClient;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'quoteOfTheDay' => $this->ipsumClient->getOne(),
        ]);
    }

    #[Route('/randomLocation', name: 'randomLocation')]
    public function randomLocation(): Response
    {
        // first see how many there are, then get a rondom one
        $locations = $this->locationClient->getAll(0, 1);
        $randomLocation = $this->locationClient->getAll(rand(0, $locations->getItemCount() - 2), 1)->current();
        return $this->redirectToRoute('locationShow', ['id' => $randomLocation->getId()]);
    }
}
