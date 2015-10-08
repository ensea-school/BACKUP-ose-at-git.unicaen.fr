<?php

namespace Application\Service\Traits;

use Application\Service\ServiceAPayer;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setServiceServiceAPayer( ServiceAPayer $serviceServiceAPayer )
    {
        $this->serviceServiceAPayer = $serviceServiceAPayer;
        return $this;
    }



    /**
     * @return ServiceAPayer
     * @throws RuntimeException
     */
    public function getServiceServiceAPayer()
    {
        if (empty($this->serviceServiceAPayer)){
        $serviceLocator = Module::$serviceLocator;
        if (! $serviceLocator) {
            if (!method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
        }
        $this->serviceServiceAPayer = $serviceLocator->get('ApplicationServiceAPayer');
        }
        return $this->serviceServiceAPayer;
    }
}