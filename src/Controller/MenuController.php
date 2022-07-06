<?php

namespace App\Controller;

use App\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    /**
     * @param Menu $data
     */
    public function __invoke(Request $request)
    {

        $data = json_decode($request->getContent(),true);
        //dd($data->burgers);
        dd($data['frites']);
        //decoder la chaine en tableau
    }

}
