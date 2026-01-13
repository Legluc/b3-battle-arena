<?php

namespace App\Controller;

use App\Repository\JoueurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(JoueurRepository $joueurRepository): Response
    {
        return $this->render('index.html.twig', [
            'joueurs' => $joueurRepository->findAll(),
        ]);
    }
}
