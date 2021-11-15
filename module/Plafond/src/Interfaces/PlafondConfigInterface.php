<?php

namespace Plafond\Interfaces;

use Application\Interfaces\ParametreEntityInterface;
use Plafond\Entity\Db\PlafondAwareInterface;
use Plafond\Entity\Db\PlafondEtat;


interface PlafondConfigInterface extends ParametreEntityInterface, PlafondAwareInterface
{
    public function getHeures(): float;



    public function setHeures(float $heures): PlafondConfigInterface;



    public function getEtatPrevu(): ?PlafondEtat;



    public function setEtatPrevu(PlafondEtat $etat): PlafondConfigInterface;



    public function getEtatRealise(): ?PlafondEtat;



    public function setEtatRealise(PlafondEtat $etat): PlafondConfigInterface;
}