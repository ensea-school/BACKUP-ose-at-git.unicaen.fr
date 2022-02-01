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
    protected ?SeuilCharge $seuilCharge = null;



    /**
     * @param SeuilCharge $seuilCharge
     *
     * @return self
     */
    public function setSeuilCharge( SeuilCharge $seuilCharge )
    {
        $this->seuilCharge = $seuilCharge;

        return $this;
    }



    public function getSeuilCharge(): ?SeuilCharge
    {
        return $this->seuilCharge;
    }
}