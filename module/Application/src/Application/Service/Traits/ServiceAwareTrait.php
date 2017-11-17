<?php

namespace Application\Service\Traits;

use Application\Service\ServiceService;

/**
 * Description of ServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceAwareTrait
{
    /**
     * @var ServiceService
     */
    private $service;



    /**
     * @param ServiceService $service
     *
     * @return self
     */
    public function setServiceService(ServiceService $service)
    {
        $this->service = $service;

        return $this;
    }



    /**
     * @return ServiceService
     */
    public function getServiceService()
    {
        if (empty($this->service)) {
            $this->service = \Application::$container->get('ApplicationService');
        }

        return $this->service;
    }
}