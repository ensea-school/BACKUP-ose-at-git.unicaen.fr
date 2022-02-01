<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Annee;

/**
 * Description of AnneeAwareTrait
 *
 * @author UnicaenCode
 */
trait AnneeAwareTrait
{
    protected ?Annee $annee = null;



    /**
     * @param Annee $annee
     *
     * @return self
     */
    public function setAnnee( Annee $annee )
    {
        $this->annee = $annee;

        return $this;
    }



    public function getAnnee(): ?Annee
    {
        return $this->annee;
    }
}