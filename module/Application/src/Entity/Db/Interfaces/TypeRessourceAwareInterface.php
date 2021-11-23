<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TypeRessource;

/**
 * Description of TypeRessourceAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeRessourceAwareInterface
{
    /**
     * @param TypeRessource $typeRessource
     * @return self
     */
    public function setTypeRessource( TypeRessource $typeRessource = null );



    /**
     * @return TypeRessource
     */
    public function getTypeRessource();
}