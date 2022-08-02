<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;

#[ApiResource(
    collectionOperations: [
        "complement"=>[
            "method" =>"get",
            "path"=>"/complements",
            'normalization_context' => ['groups' => ['get:read_catalogue']]
        ]
    ],
    itemOperations: []
)]
class Complement
{

}
