<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\SeuilCharge;

/**
 * Description of SeuilChargeAwareTrait
 *
 * @author UnicaenCode
 */
trait SeuilChargeAwareTrait
{
    /**
     * @var SeuilCharge
     */
    protected $seuilCharge;





    /**
     * @param SeuilCharge $seuilCharge
     * @return self
     */
    public function setSeuilCharge( SeuilCharge $seuilCharge = null )
    {
        $this->seuilCharge = $seuilCharge;
        return $this;
    }



    /**
     * @return SeuilCharge
     */
    public function getSeuilCharge()
    {
        return $this->seuilCharge;
    }
}