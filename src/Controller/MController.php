<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MController extends AbstractController
{
    #[Route('/m', name: 'app_m')]
    public function index(): Response
    {
        return $this->render('m/index.html.twig', [
            'controller_name' => 'MController',
        ]);
    }
}
