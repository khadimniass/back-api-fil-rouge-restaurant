<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $client = new Client();
        $generator = Factory::create("fr_FR");
        for ($i=1; $i < 5 ; $i++) { 
            $client -> setNom($generator->firstName());
            $client -> setAdresse($generator->address());
            $client -> setPrenom($generator->lastName());
            $client -> setLogin($generator->email());
            $client -> setTelephone("77 7777776");
            $client -> setRoles(["ROLE_CLIENT"]);
            $client -> setEtat(1);
            $pass = $this->hasher->hashPassword($client,"passer123");
            $client->setPassword($pass);  
            $manager->persist($client);
        }
        $manager->flush();

        
    }
}
