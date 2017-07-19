<?php

namespace Application\Service\Traits;

use Application\Service\Departement;
use Application\Module;
use RuntimeException;

/**
 * Description of DepartementAwareTrait
 *
 * @author UnicaenCode
 */
trait DepartementAwareTrait
{
    /**
     * @var Departement
     */
    private $serviceDepartement;





    /**
     * @param Departement $serviceDepartement
     * @return self
     */
    public function setServiceDepartement( Departement $serviceDepartement )
    {
        $this->serviceDepartement = $serviceDepartement;
        return $this;
    }



    /**
     * @return Departement
     * @throws RuntimeException
     */
    public function getServiceDepartement()
    {
        if (empty($this->serviceDepartement)){
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
        $this->serviceDepartement = $serviceLocator->get('ApplicationDepartement');
        }
        return $this->serviceDepartement;
    }
}