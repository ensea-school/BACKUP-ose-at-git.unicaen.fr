<?php

namespace Application\Service\Traits;

use Application\Service\DisciplineService;
use Application\Module;
use RuntimeException;

/**
 * Description of DisciplineServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait DisciplineServiceAwareTrait
{
    /**
     * @var DisciplineService
     */
    private $serviceDiscipline;





    /**
     * @param DisciplineService $serviceDiscipline
     * @return self
     */
    public function setServiceDiscipline( DisciplineService $serviceDiscipline )
    {
        $this->serviceDiscipline = $serviceDiscipline;
        return $this;
    }



    /**
     * @return DisciplineService
     * @throws RuntimeException
     */
    public function getServiceDiscipline()
    {
        if (empty($this->serviceDiscipline)){
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
        $this->serviceDiscipline = $serviceLocator->get('ApplicationDiscipline');
        }
        return $this->serviceDiscipline;
    }
}