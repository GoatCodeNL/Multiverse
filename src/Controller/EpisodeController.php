<?php

namespace App\Controller;

use App\RickMortyClient\Character\CharacterClient;
use App\RickMortyClient\Episode\EpisodeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EpisodeController extends AbstractController
{
    private EpisodeClient $episodeClient;
    private CharacterClient $characterClient;

    public function __construct(EpisodeClient $episodeClient, CharacterClient $characterClient)
    {
        $this->episodeClient = $episodeClient;
        $this->characterClient = $characterClient;
    }

    #[Route('/episode', name: 'episodeIndex')]
    public function episodeIndex(Request $request): Response
    {
        $episodesPerPage = 8;
        $page = $request->get("page") ?? 1;

        return $this->render('episode/index.html.twig', [
            'episodes' => $this->episodeClient->getAll(($page - 1) * $episodesPerPage, $episodesPerPage),
            'page' => $page,
            'itemsPerPage' => $episodesPerPage,
        ]);
    }

    #[Route('/episode/{id}', name: 'episodeShow')]
    public function episodeShow(int $id): Response
    {
        $episode = $this->episodeClient->get($id);
        return $this->render('episode/show.html.twig', [
            'episode' => $episode,
            'characters' => $this->characterClient->getBulk($episode->getCharacters()),
        ]);
    }
}
