<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\RegimeSecu;

/**
 * Description of RegimeSecuAwareTrait
 *
 * @author UnicaenCode
 */
trait RegimeSecuAwareTrait
{
    /**
     * @var RegimeSecu
     */
    private $regimeSecu;





    /**
     * @param RegimeSecu $regimeSecu
     * @return self
     */
    public function setRegimeSecu( RegimeSecu $regimeSecu = null )
    {
        $this->regimeSecu = $regimeSecu;
        return $this;
    }



    /**
     * @return RegimeSecu
     */
    public function getRegimeSecu()
    {
        return $this->regimeSecu;
    }
}