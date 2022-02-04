<?php

namespace App\Controller;

use App\RickMortyClient\BaseClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DimensionController extends AbstractController
{
//    private BaseClient $rickMortyClient;
//
//    public function __construct(BaseClient $rickMortyClient)
//    {
//        $this->rickMortyClient = $rickMortyClient;
//    }

    #[Route('/dimension', name: 'dimensionIndex')]
    public function dimensionIndex(Request $request): Response
    {
//        $dimensionsPerPage = 8;
//        $page = $request->get("page") ?? 1;
//
//        return $this->render('dimension/index.html.twig', [
//            'dimensions' => $this->rickMortyClient->getDimensions(($page - 1) * $dimensionsPerPage, $dimensionsPerPage),
//            'page' => $page,
//        ]);
    }

    #[Route('/dimension/{id}', name: 'dimensionShow')]
    public function dimensionShow(int $id): Response
    {
//        $dimension = $this->rickMortyClient->getDimension($id);
//        return $this->render('dimension/show.html.twig', [
//            'dimension' => $dimension,
//            'origin' => $this->rickMortyClient->getLocation($dimension->getOriginId()),
//            'location' => $this->rickMortyClient->getLocation($dimension->getLocationId()),
//        ]);
    }
}
