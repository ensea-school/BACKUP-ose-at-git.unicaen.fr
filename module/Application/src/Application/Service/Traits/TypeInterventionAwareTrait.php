<?php

namespace Application\Service\Traits;

use Application\Service\TypeIntervention;
use Application\Module;
use RuntimeException;

/**
 * Description of TypeInterventionAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionAwareTrait
{
    /**
     * @var TypeIntervention
     */
    private $serviceTypeIntervention;





    /**
     * @param TypeIntervention $serviceTypeIntervention
     * @return self
     */
    public function setServiceTypeIntervention( TypeIntervention $serviceTypeIntervention )
    {
        $this->serviceTypeIntervention = $serviceTypeIntervention;
        return $this;
    }



    /**
     * @return TypeIntervention
     * @throws RuntimeException
     */
    public function getServiceTypeIntervention()
    {
        if (empty($this->serviceTypeIntervention)){
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
        $this->serviceTypeIntervention = $serviceLocator->get('ApplicationTypeIntervention');
        }
        return $this->serviceTypeIntervention;
    }
}