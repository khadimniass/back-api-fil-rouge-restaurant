<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Livreur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LivreurFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $livreur = new Livreur();
        $generator = Factory::create("fr_FR");
        for ($i=1; $i < 5 ; $i++) { 
            $livreur -> setNom($generator->firstName());
            $livreur -> setMatriculeMoto(strtolower("DL5004DK"));
            $livreur -> setPrenom($generator->lastName());
            $livreur -> setLogin($generator->email());
            $livreur -> setTelephone("70 7123476");
            $livreur -> setRoles(["ROLE_LIVREUR"]);
            $livreur -> setEtat(1);
            $pass = $this->hasher->hashPassword($livreur,"passer123");
            $livreur->setPassword($pass);  
            $manager->persist($livreur);
        }
        $manager->flush();


    }
}
