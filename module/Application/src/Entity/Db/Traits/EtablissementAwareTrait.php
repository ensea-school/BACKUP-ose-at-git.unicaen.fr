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
    protected ?Etablissement $etablissement = null;



    /**
     * @param Etablissement $etablissement
     *
     * @return self
     */
    public function setEtablissement( ?Etablissement $etablissement )
    {
        $this->etablissement = $etablissement;

        return $this;
    }



    public function getEtablissement(): ?Etablissement
    {
        return $this->etablissement;
    }
}