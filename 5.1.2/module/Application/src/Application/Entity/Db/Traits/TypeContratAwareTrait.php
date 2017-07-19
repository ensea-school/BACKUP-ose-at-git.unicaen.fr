<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeContrat;

/**
 * Description of TypeContratAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeContratAwareTrait
{
    /**
     * @var TypeContrat
     */
    private $typeContrat;





    /**
     * @param TypeContrat $typeContrat
     * @return self
     */
    public function setTypeContrat( TypeContrat $typeContrat = null )
    {
        $this->typeContrat = $typeContrat;
        return $this;
    }



    /**
     * @return TypeContrat
     */
    public function getTypeContrat()
    {
        return $this->typeContrat;
    }
}