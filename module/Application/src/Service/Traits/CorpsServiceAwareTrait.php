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
    protected ?CorpsService $serviceCorps;



    /**
     * @param CorpsService|null $serviceCorps
     *
     * @return self
     */
    public function setServiceCorps( ?CorpsService $serviceCorps )
    {
        $this->serviceCorps = $serviceCorps;

        return $this;
    }



    public function getServiceCorps(): ?CorpsService
    {
        if (!$this->serviceCorps){
            $this->serviceCorps = \Application::$container->get(CorpsService::class);
        }

        return $this->serviceCorps;
    }
}