<?php

namespace Application\Service\Traits;

use Application\Service\CorpsService;

/**
 * Description of CorpsServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait CorpsServiceAwareTrait
{
    /**
     * @var CorpsService
     */
    private $serviceCorps;



    /**
     * @param CorpsService $serviceCorps
     *
     * @return self
     */
    public function setServiceCorps(CorpsService $serviceCorps)
    {
        $this->serviceCorps = $serviceCorps;

        return $this;
    }



    /**
     * @return CorpsService
     */
    public function getServiceCorps()
    {
        if (empty($this->serviceCorps)) {
            $this->serviceCorps = \Application::$container->get(CorpsService::class);
        }

        return $this->serviceCorps;
    }
}