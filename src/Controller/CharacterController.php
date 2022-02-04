<?php

namespace App\Controller;

use App\RickMortyClient\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CharacterController extends AbstractController
{
    private Client $rickMortyClient;

    public function __construct(Client $rickMortyClient)
    {
        $this->rickMortyClient = $rickMortyClient;
    }

    #[Route('/character', name: 'characterIndex')]
    public function characterIndex(Request $request): Response
    {
        $charactersPerPage = 8;
        $page = $request->get("page") ?? 1;

        return $this->render('character/index.html.twig', [
            'characters' => $this->rickMortyClient->getCharacters(($page - 1) * $charactersPerPage, $charactersPerPage),
            'page' => $page,
        ]);
    }

    #[Route('/character/{id}', name: 'characterShow')]
    public function characterShow(int $id): Response
    {
        $character = $this->rickMortyClient->getCharacter($id);
        return $this->render('character/show.html.twig', [
            'character' => $character,
//            'residents' => $this->rickMortyClient->getCharactersBulk($character->getResidents()),
        ]);
    }
}
