<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;

#[ApiResource(
    collectionOperations:[
        "catalogue"=>[
            "method"=>"get",
            "path"=>"/catalogues",
            'normalization_context' => ['groups' => ['get:read_catalogue']]
        ],
],
    itemOperations: []
)]
class Catalogue
{
}
