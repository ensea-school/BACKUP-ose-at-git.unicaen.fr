<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Parametre;

/**
 * Description of ParametreAwareInterface
 *
 * @author UnicaenCode
 */
interface ParametreAwareInterface
{
    /**
     * @param Parametre $parametre
     * @return self
     */
    public function setParametre( Parametre $parametre = null );



    /**
     * @return Parametre
     */
    public function getParametre();
}