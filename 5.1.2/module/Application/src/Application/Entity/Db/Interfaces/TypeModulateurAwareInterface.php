<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TypeModulateur;

/**
 * Description of TypeModulateurAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeModulateurAwareInterface
{
    /**
     * @param TypeModulateur $typeModulateur
     * @return self
     */
    public function setTypeModulateur( TypeModulateur $typeModulateur = null );



    /**
     * @return TypeModulateur
     */
    public function getTypeModulateur();
}