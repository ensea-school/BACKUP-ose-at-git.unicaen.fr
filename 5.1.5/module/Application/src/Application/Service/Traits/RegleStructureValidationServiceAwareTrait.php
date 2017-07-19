<?php

namespace Application\Service\Traits;

use Application\Service\RegleStructureValidationService;
use Application\Module;
use RuntimeException;

/**
 * Description of RegleStructureValidationServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait RegleStructureValidationServiceAwareTrait
{
    /**
     * @var RegleStructureValidationService
     */
    private $serviceRegleStructureValidation;





    /**
     * @param RegleStructureValidationService $serviceRegleStructureValidation
     * @return self
     */
    public function setServiceRegleStructureValidation( RegleStructureValidationService $serviceRegleStructureValidation )
    {
        $this->serviceRegleStructureValidation = $serviceRegleStructureValidation;
        return $this;
    }



    /**
     * @return RegleStructureValidationService
     * @throws RuntimeException
     */
    public function getServiceRegleStructureValidation()
    {
        if (empty($this->serviceRegleStructureValidation)){
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
            $this->serviceRegleStructureValidation = $serviceLocator->get('applicationRegleStructureValidation');
        }
        return $this->serviceRegleStructureValidation;
    }
}