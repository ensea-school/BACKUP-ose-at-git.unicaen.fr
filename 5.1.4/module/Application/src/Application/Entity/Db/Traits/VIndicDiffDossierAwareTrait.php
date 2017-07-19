<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\VIndicDiffDossier;

/**
 * Description of VIndicDiffDossierAwareTrait
 *
 * @author UnicaenCode
 */
trait VIndicDiffDossierAwareTrait
{
    /**
     * @var VIndicDiffDossier
     */
    private $vIndicDiffDossier;





    /**
     * @param VIndicDiffDossier $vIndicDiffDossier
     * @return self
     */
    public function setVIndicDiffDossier( VIndicDiffDossier $vIndicDiffDossier = null )
    {
        $this->vIndicDiffDossier = $vIndicDiffDossier;
        return $this;
    }



    /**
     * @return VIndicDiffDossier
     */
    public function getVIndicDiffDossier()
    {
        return $this->vIndicDiffDossier;
    }
}