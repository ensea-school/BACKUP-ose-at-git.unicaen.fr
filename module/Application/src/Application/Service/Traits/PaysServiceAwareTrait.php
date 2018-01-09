<?php

namespace Application\Service\Traits;

use Application\Service\PaysService;

/**
 * Description of PaysAwareTrait
 *
 * @author UnicaenCode
 */
trait PaysServiceAwareTrait
{
    /**
     * @var PaysService
     */
    private $servicePays;



    /**
     * @param PaysService $servicePays
     *
     * @return self
     */
    public function setServicePays(PaysService $servicePays)
    {
        $this->servicePays = $servicePays;

        return $this;
    }



    /**
     * @return PaysService
     */
    public function getServicePays()
    {
        if (empty($this->servicePays)) {
            $this->servicePays = \Application::$container->get(PaysService::class);
        }

        return $this->servicePays;
    }
}