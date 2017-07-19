<?php

namespace Application\Service\Traits;

use Application\Service\TypeIntervenant;
use Application\Module;
use RuntimeException;

/**
 * Description of TypeIntervenantAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeIntervenantAwareTrait
{
    /**
     * @var TypeIntervenant
     */
    private $serviceTypeIntervenant;





    /**
     * @param TypeIntervenant $serviceTypeIntervenant
     * @return self
     */
    public function setServiceTypeIntervenant( TypeIntervenant $serviceTypeIntervenant )
    {
        $this->serviceTypeIntervenant = $serviceTypeIntervenant;
        return $this;
    }



    /**
     * @return TypeIntervenant
     * @throws RuntimeException
     */
    public function getServiceTypeIntervenant()
    {
        if (empty($this->serviceTypeIntervenant)){
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
        $this->serviceTypeIntervenant = $serviceLocator->get('ApplicationTypeIntervenant');
        }
        return $this->serviceTypeIntervenant;
    }
}