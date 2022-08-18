<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Livraison;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class LivraisonPersister implements ContextAwareDataPersisterInterface
{
    private $_entitYManager;
    private $_repository;
    public function __construct(EntityManagerInterface $entityManager, CommandeRepository $repository)
    {
        $this->_entityManager=$entityManager;
        $this->_repository=$repository;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Livraison;
    }

    /**
     * @param Livraison $data
     */
    public function persist($data, array $context = [])
    {
       $prixTotal = 0 ;
        for ($i=0; $i<count($data->getCommandes()); $i++){
            for($j=$i; $j<count($data->getCommandes()); $j++){
                //if (array[i] != null && array[i].equals(array[j])) {
                if($data->getCommandes()[$i]->getZone() != $data->getCommandes()[$j]->getZone()){
                    return new JsonResponse('toute les commandes doivent etre dans une meme zone');
                }
            }
        }
   //     dd($data->getCommandes()[0]->getZone()->getPrixLivraison());
        for ($i=0; $i<count($data->getCommandes()); $i++){
            if($data->getCommandes()[$i]->getZone()){
             /*   $prixLivraison = $data->getCommandes()[$i]->getZone()->getPrixLivraison();
                $prixduproduit= $data->getCommandes()[$i]->getLigneCommandes()[$i]->getProduit()->getPrix();
                $prixTotal+=$prixduproduit+$prixLivraison; */
            }
           // dump("prix livraison", $data->getCommandes()[$i]->getZone()->getPrixLivraison());
          //  dd($data->getCommandes()[$i]->getLigneCommandes());
        }
        //dd("apres calcul prix", $prixTotal);
        //dd($data->getCommandes()[0]->getLigneCommandes()[0]->getProduit()->getprix());
        $data->setPrix($prixTotal);
        //dd("donnez avec prix",$data);
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }
    public function remove($data, array $context = [])
    {
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }
}