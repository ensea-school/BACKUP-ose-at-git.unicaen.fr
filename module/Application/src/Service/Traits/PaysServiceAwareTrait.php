<?php

namespace Application\Service\Traits;

use Application\Service\PaysService;

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
    public function setServicePays( PaysService $servicePays )
    {
        $this->servicePays = $servicePays;

        return $this;
    }



    public function getServicePays(): ?PaysService
    {
        if (empty($this->servicePays)){
            $this->servicePays = \Application::$container->get(PaysService::class);
        }

        return $this->servicePays;
    }
}