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
    /**
     * @var Annee
     */
    private $annee;





    /**
     * @param Annee $annee
     * @return self
     */
    public function setAnnee( Annee $annee = null )
    {
        $this->annee = $annee;
        return $this;
    }



    /**
     * @return Annee
     */
    public function getAnnee()
    {
        return $this->annee;
    }
}