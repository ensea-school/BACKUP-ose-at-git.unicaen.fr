<?php

namespace Application\Service\Traits;

use Application\Service\Civilite;
use Application\Module;
use RuntimeException;

/**
 * Description of CiviliteAwareTrait
 *
 * @author UnicaenCode
 */
trait CiviliteAwareTrait
{
    /**
     * @var Civilite
     */
    private $serviceCivilite;





    /**
     * @param Civilite $serviceCivilite
     * @return self
     */
    public function setServiceCivilite( Civilite $serviceCivilite )
    {
        $this->serviceCivilite = $serviceCivilite;
        return $this;
    }



    /**
     * @return Civilite
     * @throws RuntimeException
     */
    public function getServiceCivilite()
    {
        if (empty($this->serviceCivilite)){
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
        $this->serviceCivilite = $serviceLocator->get('ApplicationCivilite');
        }
        return $this->serviceCivilite;
    }
}