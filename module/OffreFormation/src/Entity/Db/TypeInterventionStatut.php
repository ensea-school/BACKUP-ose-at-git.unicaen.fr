<?php

namespace OffreFormation\Entity\Db;

use Administration\Interfaces\ParametreEntityInterface;
use Administration\Traits\ParametreEntityTrait;
use Intervenant\Entity\Db\StatutAwareTrait;
use OffreFormation\Entity\Db\Traits\TypeInterventionAwareTrait;

class TypeInterventionStatut implements ParametreEntityInterface
{
    use ParametreEntityTrait;
    use TypeInterventionAwareTrait;
    use StatutAwareTrait;

    protected float $tauxHETDService        = 1;

    protected float $tauxHETDComplementaire = 1;



    public function getTauxHETDService(): float
    {
        return $this->tauxHETDService;
    }



    public function setTauxHETDService(float $tauxHETDService): TypeInterventionStatut
    {
        $this->tauxHETDService = $tauxHETDService;

        return $this;
    }



    public function getTauxHETDComplementaire(): float
    {
        return $this->tauxHETDComplementaire;
    }



    public function setTauxHETDComplementaire(float $tauxHETDComplementaire): TypeInterventionStatut
    {
        $this->tauxHETDComplementaire = $tauxHETDComplementaire;

        return $this;
    }

}