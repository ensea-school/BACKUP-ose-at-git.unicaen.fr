<?php

namespace Application\Service\Traits;

use Application\Service\TypeAgrement;
use Application\Module;
use RuntimeException;

/**
 * Description of TypeAgrementAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeAgrementAwareTrait
{
    /**
     * @var TypeAgrement
     */
    private $serviceTypeAgrement;





    /**
     * @param TypeAgrement $serviceTypeAgrement
     * @return self
     */
    public function setServiceTypeAgrement( TypeAgrement $serviceTypeAgrement )
    {
        $this->serviceTypeAgrement = $serviceTypeAgrement;
        return $this;
    }



    /**
     * @return TypeAgrement
     * @throws RuntimeException
     */
    public function getServiceTypeAgrement()
    {
        if (empty($this->serviceTypeAgrement)){
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
        $this->serviceTypeAgrement = $serviceLocator->get('ApplicationTypeAgrement');
        }
        return $this->serviceTypeAgrement;
    }
}