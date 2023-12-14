<?php

namespace Application\Service\Traits;

use Application\Service\SeuilChargeService;

/**
 * Description of SeuilChargeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait SeuilChargeServiceAwareTrait
{
    protected ?SeuilChargeService $serviceSeuilCharge = null;



    /**
     * @param SeuilChargeService $serviceSeuilCharge
     *
     * @return self
     */
    public function setServiceSeuilCharge(?SeuilChargeService $serviceSeuilCharge)
    {
        $this->serviceSeuilCharge = $serviceSeuilCharge;

        return $this;
    }



    public function getServiceSeuilCharge(): ?SeuilChargeService
    {
        if (empty($this->serviceSeuilCharge)) {
            $this->serviceSeuilCharge = \OseAdmin::instance()->container()->get(SeuilChargeService::class);
        }

        return $this->serviceSeuilCharge;
    }
}