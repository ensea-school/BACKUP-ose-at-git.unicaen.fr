<?php

namespace Contrat\Entity\Db;

/**
 * Description of ContratAwareTrait
 *
 * @author UnicaenCode
 */
trait ContratAwareTrait
{
    protected ?Contrat $contrat = null;



    /**
     * @param Contrat $contrat
     *
     * @return self
     */
    public function setContrat( ?Contrat $contrat )
    {
        $this->contrat = $contrat;

        return $this;
    }



    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }
}