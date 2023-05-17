<?php

namespace Paiement\Entity\Db;

/**
 * Description of TypeRessourceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeRessourceAwareTrait
{
    protected ?TypeRessource $typeRessource = null;



    /**
     * @param TypeRessource $typeRessource
     *
     * @return self
     */
    public function setTypeRessource( ?TypeRessource $typeRessource )
    {
        $this->typeRessource = $typeRessource;

        return $this;
    }



    public function getTypeRessource(): ?TypeRessource
    {
        return $this->typeRessource;
    }
}