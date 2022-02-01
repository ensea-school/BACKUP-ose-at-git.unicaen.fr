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
    protected ?SeuilChargeService $serviceSeuilCharge;



    /**
     * @param SeuilChargeService|null $serviceSeuilCharge
     *
     * @return self
     */
    public function setServiceSeuilCharge( ?SeuilChargeService $serviceSeuilCharge )
    {
        $this->serviceSeuilCharge = $serviceSeuilCharge;

        return $this;
    }



    public function getServiceSeuilCharge(): ?SeuilChargeService
    {
        if (!$this->serviceSeuilCharge){
            $this->serviceSeuilCharge = \Application::$container->get(SeuilChargeService::class);
        }

        return $this->serviceSeuilCharge;
    }
}