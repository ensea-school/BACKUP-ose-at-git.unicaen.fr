<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Civilite;

/**
 * Description of CiviliteAwareInterface
 *
 * @author UnicaenCode
 */
interface CiviliteAwareInterface
{
    /**
     * @param Civilite $civilite
     * @return self
     */
    public function setCivilite( Civilite $civilite = null );



    /**
     * @return Civilite
     */
    public function getCivilite();
}