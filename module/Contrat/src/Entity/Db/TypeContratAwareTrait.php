<?php

namespace Contrat\Entity\Db;

/**
 * Description of TypeContratAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeContratAwareTrait
{
    protected ?TypeContrat $typeContrat = null;



    /**
     * @param TypeContrat $typeContrat
     *
     * @return self
     */
    public function setTypeContrat( ?TypeContrat $typeContrat )
    {
        $this->typeContrat = $typeContrat;

        return $this;
    }



    public function getTypeContrat(): ?TypeContrat
    {
        return $this->typeContrat;
    }
}