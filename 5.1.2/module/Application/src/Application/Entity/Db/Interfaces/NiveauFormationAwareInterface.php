<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\NiveauFormation;

/**
 * Description of NiveauFormationAwareInterface
 *
 * @author UnicaenCode
 */
interface NiveauFormationAwareInterface
{
    /**
     * @param NiveauFormation $niveauFormation
     * @return self
     */
    public function setNiveauFormation( NiveauFormation $niveauFormation = null );



    /**
     * @return NiveauFormation
     */
    public function getNiveauFormation();
}