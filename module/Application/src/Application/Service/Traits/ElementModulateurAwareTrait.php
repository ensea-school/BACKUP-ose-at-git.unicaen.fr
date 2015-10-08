<?php

namespace Application\Service\Traits;

use Application\Service\ElementModulateur;
use Application\Module;
use RuntimeException;

/**
 * Description of ElementModulateurAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementModulateurAwareTrait
{
    /**
     * @var ElementModulateur
     */
    private $serviceElementModulateur;





    /**
     * @param ElementModulateur $serviceElementModulateur
     * @return self
     */
    public function setServiceElementModulateur( ElementModulateur $serviceElementModulateur )
    {
        $this->serviceElementModulateur = $serviceElementModulateur;
        return $this;
    }



    /**
     * @return ElementModulateur
     * @throws RuntimeException
     */
    public function getServiceElementModulateur()
    {
        if (empty($this->serviceElementModulateur)){
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
        $this->serviceElementModulateur = $serviceLocator->get('ApplicationElementModulateur');
        }
        return $this->serviceElementModulateur;
    }
}