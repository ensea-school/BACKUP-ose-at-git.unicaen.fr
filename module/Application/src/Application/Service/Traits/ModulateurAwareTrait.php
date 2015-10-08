<?php

namespace Application\Service\Traits;

use Application\Service\Modulateur;
use Application\Module;
use RuntimeException;

/**
 * Description of ModulateurAwareTrait
 *
 * @author UnicaenCode
 */
trait ModulateurAwareTrait
{
    /**
     * @var Modulateur
     */
    private $serviceModulateur;





    /**
     * @param Modulateur $serviceModulateur
     * @return self
     */
    public function setServiceModulateur( Modulateur $serviceModulateur )
    {
        $this->serviceModulateur = $serviceModulateur;
        return $this;
    }



    /**
     * @return Modulateur
     * @throws RuntimeException
     */
    public function getServiceModulateur()
    {
        if (empty($this->serviceModulateur)){
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
        $this->serviceModulateur = $serviceLocator->get('ApplicationModulateur');
        }
        return $this->serviceModulateur;
    }
}