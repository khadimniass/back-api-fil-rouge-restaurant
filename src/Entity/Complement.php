<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;

#[ApiResource(
    collectionOperations: [
        "complement"=>[
            "method" =>"get",
            "path"=>"/complements"
        ]
    ],
    itemOperations: []
)]
class Complement
{

}
