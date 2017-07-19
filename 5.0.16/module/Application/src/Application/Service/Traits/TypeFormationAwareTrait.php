<?php

namespace Application\Service\Traits;

use Application\Service\TypeFormation;
use Application\Module;
use RuntimeException;

/**
 * Description of TypeFormationAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeFormationAwareTrait
{
    /**
     * @var TypeFormation
     */
    private $serviceTypeFormation;





    /**
     * @param TypeFormation $serviceTypeFormation
     * @return self
     */
    public function setServiceTypeFormation( TypeFormation $serviceTypeFormation )
    {
        $this->serviceTypeFormation = $serviceTypeFormation;
        return $this;
    }



    /**
     * @return TypeFormation
     * @throws RuntimeException
     */
    public function getServiceTypeFormation()
    {
        if (empty($this->serviceTypeFormation)){
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
        $this->serviceTypeFormation = $serviceLocator->get('ApplicationTypeFormation');
        }
        return $this->serviceTypeFormation;
    }
}