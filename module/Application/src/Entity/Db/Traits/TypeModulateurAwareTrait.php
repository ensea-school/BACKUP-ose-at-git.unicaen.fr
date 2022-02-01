<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeModulateur;

/**
 * Description of TypeModulateurAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeModulateurAwareTrait
{
    protected ?TypeModulateur $typeModulateur;



    /**
     * @param TypeModulateur|null $typeModulateur
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