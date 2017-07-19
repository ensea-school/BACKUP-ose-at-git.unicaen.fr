<?php

namespace Application\Service\Traits;

use Application\Service\Pays;
use Application\Module;
use RuntimeException;

/**
 * Description of PaysAwareTrait
 *
 * @author UnicaenCode
 */
trait PaysAwareTrait
{
    /**
     * @var Pays
     */
    private $servicePays;





    /**
     * @param Pays $servicePays
     * @return self
     */
    public function setServicePays( Pays $servicePays )
    {
        $this->servicePays = $servicePays;
        return $this;
    }



    /**
     * @return Pays
     * @throws RuntimeException
     */
    public function getServicePays()
    {
        if (empty($this->servicePays)){
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
        $this->servicePays = $serviceLocator->get('ApplicationPays');
        }
        return $this->servicePays;
    }
}