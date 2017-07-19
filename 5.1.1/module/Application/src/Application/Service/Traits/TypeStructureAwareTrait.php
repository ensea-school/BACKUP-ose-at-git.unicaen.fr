<?php

namespace Application\Service\Traits;

use Application\Service\TypeStructure;
use Application\Module;
use RuntimeException;

/**
 * Description of TypeStructureAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeStructureAwareTrait
{
    /**
     * @var TypeStructure
     */
    private $serviceTypeStructure;





    /**
     * @param TypeStructure $serviceTypeStructure
     * @return self
     */
    public function setServiceTypeStructure( TypeStructure $serviceTypeStructure )
    {
        $this->serviceTypeStructure = $serviceTypeStructure;
        return $this;
    }



    /**
     * @return TypeStructure
     * @throws RuntimeException
     */
    public function getServiceTypeStructure()
    {
        if (empty($this->serviceTypeStructure)){
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
        $this->serviceTypeStructure = $serviceLocator->get('ApplicationTypeStructure');
        }
        return $this->serviceTypeStructure;
    }
}