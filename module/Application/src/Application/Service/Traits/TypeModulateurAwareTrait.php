<?php

namespace Application\Service\Traits;

use Application\Service\TypeModulateur;
use Application\Module;
use RuntimeException;

/**
 * Description of TypeModulateurAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeModulateurAwareTrait
{
    /**
     * @var TypeModulateur
     */
    private $serviceTypeModulateur;





    /**
     * @param TypeModulateur $serviceTypeModulateur
     * @return self
     */
    public function setServiceTypeModulateur( TypeModulateur $serviceTypeModulateur )
    {
        $this->serviceTypeModulateur = $serviceTypeModulateur;
        return $this;
    }



    /**
     * @return TypeModulateur
     * @throws RuntimeException
     */
    public function getServiceTypeModulateur()
    {
        if (empty($this->serviceTypeModulateur)){
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
        $this->serviceTypeModulateur = $serviceLocator->get('ApplicationTypeModulateur');
        }
        return $this->serviceTypeModulateur;
    }
}