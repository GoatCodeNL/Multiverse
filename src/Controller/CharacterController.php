<?php

namespace App\Controller;

use App\RickMortyClient\Character\CharacterClient;
use App\RickMortyClient\Episode\EpisodeClient;
use App\RickMortyClient\Location\LocationClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CharacterController extends AbstractController
{
    private CharacterClient $characterClient;
    private LocationClient $locationClient;
    private EpisodeClient $episodeClient;

    public function __construct(CharacterClient $characterClient, LocationClient $locationClient, EpisodeClient $episodeClient)
    {
        $this->characterClient = $characterClient;
        $this->locationClient = $locationClient;
        $this->episodeClient = $episodeClient;
    }

    #[Route('/character', name: 'characterIndex')]
    public function characterIndex(Request $request): Response
    {
        $charactersPerPage = 8;
        $page = $request->get("page") ?? 1;

        return $this->render('character/index.html.twig', [
            'characters' => $this->characterClient->getAll(($page - 1) * $charactersPerPage, $charactersPerPage),
            'page' => $page,
            'itemsPerPage' => $charactersPerPage,
        ]);
    }

    #[Route('/character/{id}', name: 'characterShow')]
    public function characterShow(int $id): Response
    {
        $character = $this->characterClient->get($id);
        return $this->render('character/show.html.twig', [
            'character' => $character,
            'origin' => $this->locationClient->get($character->getOriginId()),
            'location' => $this->locationClient->get($character->getLocationId()),
            'episodes' => $this->episodeClient->getBulk($character->getEpisode()),
        ]);
    }
}
