<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Annee;

/**
 * Description of AnneeAwareInterface
 *
 * @author UnicaenCode
 */
interface AnneeAwareInterface
{
    /**
     * @param Annee $annee
     * @return self
     */
    public function setAnnee( Annee $annee = null );



    /**
     * @return Annee
     */
    public function getAnnee();
}