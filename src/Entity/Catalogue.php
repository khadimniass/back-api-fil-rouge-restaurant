<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;

#[ApiResource(
    collectionOperations:[
        "catalogue"=>[
            "method"=>"get",
            "path"=>"/catalogues"
        ],
],
    itemOperations: []
)]
class Catalogue
{
}
