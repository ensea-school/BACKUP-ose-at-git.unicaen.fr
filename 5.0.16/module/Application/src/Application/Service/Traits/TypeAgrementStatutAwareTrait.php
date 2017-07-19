<?php

namespace Application\Service\Traits;

use Application\Service\TypeAgrementStatut;
use Application\Module;
use RuntimeException;

/**
 * Description of TypeAgrementStatutAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeAgrementStatutAwareTrait
{
    /**
     * @var TypeAgrementStatut
     */
    private $serviceTypeAgrementStatut;





    /**
     * @param TypeAgrementStatut $serviceTypeAgrementStatut
     * @return self
     */
    public function setServiceTypeAgrementStatut( TypeAgrementStatut $serviceTypeAgrementStatut )
    {
        $this->serviceTypeAgrementStatut = $serviceTypeAgrementStatut;
        return $this;
    }



    /**
     * @return TypeAgrementStatut
     * @throws RuntimeException
     */
    public function getServiceTypeAgrementStatut()
    {
        if (empty($this->serviceTypeAgrementStatut)){
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
        $this->serviceTypeAgrementStatut = $serviceLocator->get('ApplicationTypeAgrementStatut');
        }
        return $this->serviceTypeAgrementStatut;
    }
}