<?php

namespace Application\Service\Traits;

use Application\Service\CentreCout;
use Application\Module;
use RuntimeException;

/**
 * Description of CentreCoutAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutAwareTrait
{
    /**
     * @var CentreCout
     */
    private $serviceCentreCout;





    /**
     * @param CentreCout $serviceCentreCout
     * @return self
     */
    public function setServiceCentreCout( CentreCout $serviceCentreCout )
    {
        $this->serviceCentreCout = $serviceCentreCout;
        return $this;
    }



    /**
     * @return CentreCout
     * @throws RuntimeException
     */
    public function getServiceCentreCout()
    {
        if (empty($this->serviceCentreCout)){
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
        $this->serviceCentreCout = $serviceLocator->get('ApplicationCentreCout');
        }
        return $this->serviceCentreCout;
    }
}