<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TypeHeures;

/**
 * Description of TypeHeuresAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeHeuresAwareInterface
{
    /**
     * @param TypeHeures $typeHeures
     * @return self
     */
    public function setTypeHeures( TypeHeures $typeHeures = null );



    /**
     * @return TypeHeures
     */
    public function getTypeHeures();
}