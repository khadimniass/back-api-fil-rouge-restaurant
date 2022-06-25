<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Gestionnaire;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GestionnaireFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $gestionnaire = new Gestionnaire();
        // $generator = Factory::create("fr_FR");
        for ($i=1; $i < 5 ; $i++) { 
            $gestionnaire -> setNom("NDIAYE");
            $gestionnaire -> setPrenom("Adama");
            $gestionnaire -> setLogin("adama@gmail.com");
            $gestionnaire -> setTelephone("70 7123476");
            $gestionnaire -> setRoles(["ROLE_GESTIONNAIRE"]);
            $gestionnaire -> setEtat(1);
            $pass = $this->hasher->hashPassword($gestionnaire,"passer123");
            $gestionnaire->setPassword($pass);  
            $manager->persist($gestionnaire);
        }
        $manager->flush();


    }
}
