<?php

namespace Application\Service\Traits;

use Application\Service\SeuilChargeService;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setServiceSeuilCharge( SeuilChargeService $serviceSeuilCharge )
    {
        $this->serviceSeuilCharge = $serviceSeuilCharge;
        return $this;
    }



    /**
     * @return SeuilChargeService
     * @throws RuntimeException
     */
    public function getServiceSeuilCharge()
    {
        if (empty($this->serviceSeuilCharge)){
            $serviceLocator = Module::$serviceLocator;
            if (! $serviceLocator) {
                if (!method_exists($this, 'getServiceLocator')) {
                    throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
                }

                $serviceLocator = $this->getServiceLocator();
                if (method_exists($serviceLocator, 'getServiceLocator')) {
                    $serviceLocator = $serviceLocator->getServiceLocator();
                }
            }
            $this->serviceSeuilCharge = $serviceLocator->get('applicationSeuilCharge');
        }
        return $this->serviceSeuilCharge;
    }
}