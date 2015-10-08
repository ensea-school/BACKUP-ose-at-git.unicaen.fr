<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Modulateur;

/**
 * Description of ModulateurAwareInterface
 *
 * @author UnicaenCode
 */
interface ModulateurAwareInterface
{
    /**
     * @param Modulateur $modulateur
     * @return self
     */
    public function setModulateur( Modulateur $modulateur = null );



    /**
     * @return Modulateur
     */
    public function getModulateur();
}