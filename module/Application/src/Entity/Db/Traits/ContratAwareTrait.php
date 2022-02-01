<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Contrat;

/**
 * Description of ContratAwareTrait
 *
 * @author UnicaenCode
 */
trait ContratAwareTrait
{
    protected ?Contrat $contrat;



    /**
     * @param Contrat|null $contrat
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