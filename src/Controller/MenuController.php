<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Repository\BurgerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends AbstractController
{
    /**
     * @param Menu $data
     */
    public function __invoke(Request $request,EntityManagerInterface $entityManager, BurgerRepository $repository)
    {
        $data = json_decode($request->getContent(),true);
        dd($data['frites']);
        //decoder la chaine en tableau
    }
}