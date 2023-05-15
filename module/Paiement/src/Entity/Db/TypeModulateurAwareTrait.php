<?php

namespace Paiement\Entity\Db;

/**
 * Description of TypeModulateurAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeModulateurAwareTrait
{
    protected ?TypeModulateur $typeModulateur = null;



    /**
     * @param TypeModulateur $typeModulateur
     *
     * @return self
     */
    public function setTypeModulateur( ?TypeModulateur $typeModulateur )
    {
        $this->typeModulateur = $typeModulateur;

        return $this;
    }



    public function getTypeModulateur(): ?TypeModulateur
    {
        return $this->typeModulateur;
    }
}