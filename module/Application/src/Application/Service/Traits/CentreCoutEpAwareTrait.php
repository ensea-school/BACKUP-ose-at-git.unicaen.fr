<?php

namespace Application\Service\Traits;

use Application\Service\CentreCoutEp;
use Application\Module;
use RuntimeException;

/**
 * Description of CentreCoutEpAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutEpAwareTrait
{
    /**
     * @var CentreCoutEp
     */
    private $serviceCentreCoutEp;





    /**
     * @param CentreCoutEp $serviceCentreCoutEp
     * @return self
     */
    public function setServiceCentreCoutEp( CentreCoutEp $serviceCentreCoutEp )
    {
        $this->serviceCentreCoutEp = $serviceCentreCoutEp;
        return $this;
    }



    /**
     * @return CentreCoutEp
     * @throws RuntimeException
     */
    public function getServiceCentreCoutEp()
    {
        if (empty($this->serviceCentreCoutEp)){
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
        $this->serviceCentreCoutEp = $serviceLocator->get('ApplicationCentreCoutEp');
        }
        return $this->serviceCentreCoutEp;
    }
}