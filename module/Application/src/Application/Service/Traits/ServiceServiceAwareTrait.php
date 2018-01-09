<?php

namespace Application\Service\Traits;

use Application\Service\ServiceService;

/**
 * Description of ServiceServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceServiceAwareTrait
{
    /**
     * @var ServiceService
     */
    private $serviceService;



    /**
     * @param ServiceService $serviceService
     *
     * @return self
     */
    public function setServiceService(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;

        return $this;
    }



    /**
     * @return ServiceService
     */
    public function getServiceService()
    {
        if (empty($this->serviceService)) {
            $this->serviceService = \Application::$container->get(ServiceService::class);
        }

        return $this->serviceService;
    }
}