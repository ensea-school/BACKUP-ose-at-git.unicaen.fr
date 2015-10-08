<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeAgrement;

/**
 * Description of TypeAgrementAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeAgrementAwareTrait
{
    /**
     * @var TypeAgrement
     */
    private $typeAgrement;





    /**
     * @param TypeAgrement $typeAgrement
     * @return self
     */
    public function setTypeAgrement( TypeAgrement $typeAgrement = null )
    {
        $this->typeAgrement = $typeAgrement;
        return $this;
    }



    /**
     * @return TypeAgrement
     */
    public function getTypeAgrement()
    {
        return $this->typeAgrement;
    }
}