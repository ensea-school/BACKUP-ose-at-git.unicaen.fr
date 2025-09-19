<?php

namespace Lieu\Service;

/**
 * Description of PaysServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait PaysServiceAwareTrait
{
    protected ?PaysService $servicePays = null;



    /**
     * @param PaysService $servicePays
     *
     * @return self
     */
    public function setServicePays(?PaysService $servicePays)
    {
        $this->servicePays = $servicePays;

        return $this;
    }



    public function getServicePays(): ?PaysService
    {
        if (empty($this->servicePays)) {
            $this->servicePays = \Framework\Application\Application::getInstance()->container()->get(PaysService::class);
        }

        return $this->servicePays;
    }
}