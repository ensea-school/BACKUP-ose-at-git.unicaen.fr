<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeHeures;

/**
 * Description of TypeHeuresAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeHeuresAwareTrait
{
    /**
     * @var TypeHeures
     */
    private $typeHeures;





    /**
     * @param TypeHeures $typeHeures
     * @return self
     */
    public function setTypeHeures( TypeHeures $typeHeures = null )
    {
        $this->typeHeures = $typeHeures;
        return $this;
    }



    /**
     * @return TypeHeures
     */
    public function getTypeHeures()
    {
        return $this->typeHeures;
    }
}