<?php

namespace Application\Service\Traits;

use Application\Service\Structure;
use Application\Module;
use RuntimeException;

/**
 * Description of StructureAwareTrait
 *
 * @author UnicaenCode
 */
trait StructureAwareTrait
{
    /**
     * @var Structure
     */
    private $serviceStructure;





    /**
     * @param Structure $serviceStructure
     * @return self
     */
    public function setServiceStructure( Structure $serviceStructure )
    {
        $this->serviceStructure = $serviceStructure;
        return $this;
    }



    /**
     * @return Structure
     * @throws RuntimeException
     */
    public function getServiceStructure()
    {
        if (empty($this->serviceStructure)){
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
        $this->serviceStructure = $serviceLocator->get('ApplicationStructure');
        }
        return $this->serviceStructure;
    }
}