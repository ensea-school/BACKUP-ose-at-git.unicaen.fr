<?php

namespace Application\Service\Traits;

use Application\Service\TypeInterventionStructureService;
use Application\Module;
use RuntimeException;

/**
 * Description of TypeInterventionStructureServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionStructureServiceAwareTrait
{
    /**
     * @var TypeInterventionStructureService
     */
    private $serviceTypeInterventionStructure;





    /**
     * @param TypeInterventionStructureService $serviceTypeInterventionStructure
     * @return self
     */
    public function setServiceTypeInterventionStructure( TypeInterventionStructureService $serviceTypeInterventionStructure )
    {
        $this->serviceTypeInterventionStructure = $serviceTypeInterventionStructure;
        return $this;
    }



    /**
     * @return TypeInterventionStructureService
     * @throws RuntimeException
     */
    public function getServiceTypeInterventionStructure()
    {
        if (empty($this->serviceTypeInterventionStructure)){
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
            $this->serviceTypeInterventionStructure = $serviceLocator->get('applicationTypeInterventionStructure');
        }
        return $this->serviceTypeInterventionStructure;
    }
}