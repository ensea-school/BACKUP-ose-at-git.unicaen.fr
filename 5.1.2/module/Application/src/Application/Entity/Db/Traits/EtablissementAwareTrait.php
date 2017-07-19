<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Etablissement;

/**
 * Description of EtablissementAwareTrait
 *
 * @author UnicaenCode
 */
trait EtablissementAwareTrait
{
    /**
     * @var Etablissement
     */
    private $etablissement;





    /**
     * @param Etablissement $etablissement
     * @return self
     */
    public function setEtablissement( Etablissement $etablissement = null )
    {
        $this->etablissement = $etablissement;
        return $this;
    }



    /**
     * @return Etablissement
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }
}