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
    /**
     * @var Contrat
     */
    private $contrat;





    /**
     * @param Contrat $contrat
     * @return self
     */
    public function setContrat( Contrat $contrat = null )
    {
        $this->contrat = $contrat;
        return $this;
    }



    /**
     * @return Contrat
     */
    public function getContrat()
    {
        return $this->contrat;
    }
}