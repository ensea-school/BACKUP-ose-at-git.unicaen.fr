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
    protected ?TypeHeures $typeHeures = null;



    /**
     * @param TypeHeures $typeHeures
     *
     * @return self
     */
    public function setTypeHeures( TypeHeures $typeHeures )
    {
        $this->typeHeures = $typeHeures;

        return $this;
    }



    public function getTypeHeures(): ?TypeHeures
    {
        return $this->typeHeures;
    }
}