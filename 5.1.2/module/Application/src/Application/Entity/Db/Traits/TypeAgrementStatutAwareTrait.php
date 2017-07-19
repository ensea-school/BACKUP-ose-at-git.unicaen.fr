<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeAgrementStatut;

/**
 * Description of TypeAgrementStatutAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeAgrementStatutAwareTrait
{
    /**
     * @var TypeAgrementStatut
     */
    private $typeAgrementStatut;





    /**
     * @param TypeAgrementStatut $typeAgrementStatut
     * @return self
     */
    public function setTypeAgrementStatut( TypeAgrementStatut $typeAgrementStatut = null )
    {
        $this->typeAgrementStatut = $typeAgrementStatut;
        return $this;
    }



    /**
     * @return TypeAgrementStatut
     */
    public function getTypeAgrementStatut()
    {
        return $this->typeAgrementStatut;
    }
}