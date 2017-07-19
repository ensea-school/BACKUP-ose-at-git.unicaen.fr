<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Dossier;

/**
 * Description of DossierAwareInterface
 *
 * @author UnicaenCode
 */
interface DossierAwareInterface
{
    /**
     * @param Dossier $dossier
     * @return self
     */
    public function setDossier( Dossier $dossier = null );



    /**
     * @return Dossier
     */
    public function getDossier();
}