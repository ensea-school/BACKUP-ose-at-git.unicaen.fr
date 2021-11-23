<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TypeCentreCout;

/**
 * Description of TypeCentreCoutAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeCentreCoutAwareInterface
{
    /**
     * @param TypeCentreCout $typeCentreCout
     * @return self
     */
    public function setTypeCentreCout( TypeCentreCout $typeCentreCout = null );



    /**
     * @return TypeCentreCout
     */
    public function getTypeCentreCout();
}