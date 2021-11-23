<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TypeAgrement;

/**
 * Description of TypeAgrementAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeAgrementAwareInterface
{
    /**
     * @param TypeAgrement $typeAgrement
     * @return self
     */
    public function setTypeAgrement( TypeAgrement $typeAgrement = null );



    /**
     * @return TypeAgrement
     */
    public function getTypeAgrement();
}