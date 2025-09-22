<?php

namespace Intervenant\Service;

/**
 * Description of CorpsServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait CorpsServiceAwareTrait
{
    protected ?CorpsService $serviceCorps = null;



    /**
     * @param CorpsService $serviceCorps
     *
     * @return self
     */
    public function setServiceCorps(?CorpsService $serviceCorps)
    {
        $this->serviceCorps = $serviceCorps;

        return $this;
    }



    public function getServiceCorps(): ?CorpsService
    {
        if (empty($this->serviceCorps)) {
            $this->serviceCorps = \Framework\Application\Application::getInstance()->container()->get(CorpsService::class);
        }

        return $this->serviceCorps;
    }
}