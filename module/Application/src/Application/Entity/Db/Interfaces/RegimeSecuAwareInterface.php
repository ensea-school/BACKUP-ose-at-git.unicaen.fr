<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\RegimeSecu;

/**
 * Description of RegimeSecuAwareInterface
 *
 * @author UnicaenCode
 */
interface RegimeSecuAwareInterface
{
    /**
     * @param RegimeSecu $regimeSecu
     * @return self
     */
    public function setRegimeSecu( RegimeSecu $regimeSecu = null );



    /**
     * @return RegimeSecu
     */
    public function getRegimeSecu();
}