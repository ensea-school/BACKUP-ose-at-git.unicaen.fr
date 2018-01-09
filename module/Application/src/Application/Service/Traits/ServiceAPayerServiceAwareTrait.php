<?php

namespace Application\Service\Traits;

use Application\Service\ServiceAPayerService;

/**
 * Description of ServiceAPayerAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceAPayerServiceAwareTrait
{
    /**
     * @var ServiceAPayerService
     */
    private $serviceServiceAPayer;



    /**
     * @param ServiceAPayerService $serviceServiceAPayer
     *
     * @return self
     */
    public function setServiceServiceAPayer(ServiceAPayerService $serviceServiceAPayer)
    {
        $this->serviceServiceAPayer = $serviceServiceAPayer;

        return $this;
    }



    /**
     * @return ServiceAPayerService
     */
    public function getServiceServiceAPayer()
    {
        if (empty($this->serviceServiceAPayer)) {
            $this->serviceServiceAPayer = \Application::$container->get(ServiceAPayerService::class);
        }

        return $this->serviceServiceAPayer;
    }
}