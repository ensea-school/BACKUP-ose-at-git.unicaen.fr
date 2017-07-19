<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Dossier;

/**
 * Description of DossierAwareTrait
 *
 * @author UnicaenCode
 */
trait DossierAwareTrait
{
    /**
     * @var Dossier
     */
    private $dossier;





    /**
     * @param Dossier $dossier
     * @return self
     */
    public function setDossier( Dossier $dossier = null )
    {
        $this->dossier = $dossier;
        return $this;
    }



    /**
     * @return Dossier
     */
    public function getDossier()
    {
        return $this->dossier;
    }
}