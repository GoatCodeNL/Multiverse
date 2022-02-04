<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlumbusController extends AbstractController
{
    #[Route('/plumbus', name: 'plumbus')]
    public function index(): Response
    {
        return $this->render('plumbus/index.html.twig', [
            'controller_name' => 'PlumbusController',
        ]);
    }
}
