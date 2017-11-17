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
    /**
     * @var SeuilChargeService
     */
    private $serviceSeuilCharge;



    /**
     * @param SeuilChargeService $serviceSeuilCharge
     *
     * @return self
     */
    public function setServiceSeuilCharge(SeuilChargeService $serviceSeuilCharge)
    {
        $this->serviceSeuilCharge = $serviceSeuilCharge;

        return $this;
    }



    /**
     * @return SeuilChargeService
     */
    public function getServiceSeuilCharge()
    {
        if (empty($this->serviceSeuilCharge)) {
            $this->serviceSeuilCharge = \Application::$container->get('applicationSeuilCharge');
        }

        return $this->serviceSeuilCharge;
    }
}