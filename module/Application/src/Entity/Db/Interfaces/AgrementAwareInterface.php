<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Agrement;

/**
 * Description of AgrementAwareInterface
 *
 * @author UnicaenCode
 */
interface AgrementAwareInterface
{
    /**
     * @param Agrement $agrement
     * @return self
     */
    public function setAgrement( Agrement $agrement = null );



    /**
     * @return Agrement
     */
    public function getAgrement();
}