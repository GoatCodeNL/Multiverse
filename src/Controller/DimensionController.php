<?php

namespace App\Controller;

use App\RickMortyClient\Character\CharacterClient;
use App\RickMortyClient\Dimension\DimensionClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DimensionController extends AbstractController
{
    private DimensionClient $dimensionClient;
    private CharacterClient $characterClient;

    public function __construct(DimensionClient $dimensionClient, CharacterClient $characterClient)
    {
        $this->dimensionClient = $dimensionClient;
        $this->characterClient = $characterClient;
    }

    #[Route('/dimension', name: 'dimensionIndex')]
    public function dimensionIndex(Request $request): Response
    {
        $dimensionsPerPage = 9;
        $page = $request->get("page") ?? 1;

        return $this->render('dimension/index.html.twig', [
            'dimensions' => $this->dimensionClient->getAll(($page - 1) * $dimensionsPerPage, $dimensionsPerPage),
            'page' => $page,
            'itemsPerPage' => $dimensionsPerPage,
        ]);
    }

    #[Route('/dimension/{id}', name: 'dimensionShow')]
    public function dimensionShow(int $id): Response
    {
        $dimension = $this->dimensionClient->get($id);
        return $this->render('dimension/show.html.twig', [
            'dimension' => $dimension,
            'residents' => $this->characterClient->getBulk($dimension->getResidents()),
        ]);
    }
}
