<?php

namespace Application\Service\Traits;

use Application\Service\TypeModulateurStructureService;
use Application\Module;
use RuntimeException;

/**
 * Description of TypeModulateurStructureServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeModulateurStructureServiceAwareTrait
{
    /**
     * @var TypeModulateurStructureService
     */
    private $serviceTypeModulateurStructure;





    /**
     * @param TypeModulateurStructureService $serviceTypeModulateurStructure
     * @return self
     */
    public function setServiceTypeModulateurStructure( TypeModulateurStructureService $serviceTypeModulateurStructure )
    {
        $this->serviceTypeModulateurStructure = $serviceTypeModulateurStructure;
        return $this;
    }



    /**
     * @return TypeModulateurStructureService
     * @throws RuntimeException
     */
    public function getServiceTypeModulateurStructure()
    {
        if (empty($this->serviceTypeModulateurStructure)){
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
            $this->serviceTypeModulateurStructure = $serviceLocator->get('applicationTypeModulateurStructure');
        }
        return $this->serviceTypeModulateurStructure;
    }
}