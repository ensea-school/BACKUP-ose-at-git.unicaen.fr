<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TypeAgrementStatut;

/**
 * Description of TypeAgrementStatutAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeAgrementStatutAwareInterface
{
    /**
     * @param TypeAgrementStatut $typeAgrementStatut
     * @return self
     */
    public function setTypeAgrementStatut( TypeAgrementStatut $typeAgrementStatut = null );



    /**
     * @return TypeAgrementStatut
     */
    public function getTypeAgrementStatut();
}