<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TypeFormation;

/**
 * Description of TypeFormationAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeFormationAwareInterface
{
    /**
     * @param TypeFormation $typeFormation
     * @return self
     */
    public function setTypeFormation( TypeFormation $typeFormation = null );



    /**
     * @return TypeFormation
     */
    public function getTypeFormation();
}