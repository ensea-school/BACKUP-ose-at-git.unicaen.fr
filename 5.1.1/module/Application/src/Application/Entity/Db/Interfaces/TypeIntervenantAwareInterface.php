<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TypeIntervenant;

/**
 * Description of TypeIntervenantAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeIntervenantAwareInterface
{
    /**
     * @param TypeIntervenant $typeIntervenant
     * @return self
     */
    public function setTypeIntervenant( TypeIntervenant $typeIntervenant = null );



    /**
     * @return TypeIntervenant
     */
    public function getTypeIntervenant();
}