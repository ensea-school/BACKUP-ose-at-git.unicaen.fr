<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\VIndicDiffDossier;

/**
 * Description of VIndicDiffDossierAwareInterface
 *
 * @author UnicaenCode
 */
interface VIndicDiffDossierAwareInterface
{
    /**
     * @param VIndicDiffDossier $vIndicDiffDossier
     * @return self
     */
    public function setVIndicDiffDossier( VIndicDiffDossier $vIndicDiffDossier = null );



    /**
     * @return VIndicDiffDossier
     */
    public function getVIndicDiffDossier();
}