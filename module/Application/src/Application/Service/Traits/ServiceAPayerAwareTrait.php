<?php

namespace Application\Service\Traits;

use Application\Service\ServiceAPayer;

/**
 * Description of ServiceAPayerAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceAPayerAwareTrait
{
    /**
     * @var ServiceAPayer
     */
    private $serviceServiceAPayer;



    /**
     * @param ServiceAPayer $serviceServiceAPayer
     *
     * @return self
     */
    public function setServiceServiceAPayer(ServiceAPayer $serviceServiceAPayer)
    {
        $this->serviceServiceAPayer = $serviceServiceAPayer;

        return $this;
    }



    /**
     * @return ServiceAPayer
     */
    public function getServiceServiceAPayer()
    {
        if (empty($this->serviceServiceAPayer)) {
            $this->serviceServiceAPayer = \Application::$container->get('ApplicationServiceAPayer');
        }

        return $this->serviceServiceAPayer;
    }
}