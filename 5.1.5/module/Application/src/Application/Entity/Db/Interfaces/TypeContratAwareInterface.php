<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TypeContrat;

/**
 * Description of TypeContratAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeContratAwareInterface
{
    /**
     * @param TypeContrat $typeContrat
     * @return self
     */
    public function setTypeContrat( TypeContrat $typeContrat = null );



    /**
     * @return TypeContrat
     */
    public function getTypeContrat();
}