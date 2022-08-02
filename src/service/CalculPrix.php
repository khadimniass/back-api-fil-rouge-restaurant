<?php

namespace App\service;

use App\Entity\{Burger, Boisson, Frite,};

class CalculPrix
{
  public static function prixMenu($menu,$pourcentage): float
  {
    $prix = 10700;
    /*
    foreach ($menu->getMenuBoissons() as $boisson) {
      $prix += $boisson->getBoisson()->getPrix() * $boisson->getQuantiteboisson();
    }
    foreach ($menu->getMenuBurgers() as $burger) {
      $prix += $burger->getBurgers()->getPrix() * $burger->getQuantiteBurger();
    }
    foreach ($menu->getMenuFrites() as $frite) {
      $prix += $frite->getFrite()->getPrix() * $frite->getQuantiteFrite();
    }
    return $prix*$pourcentage;
    */
      return $prix;
  }

}
