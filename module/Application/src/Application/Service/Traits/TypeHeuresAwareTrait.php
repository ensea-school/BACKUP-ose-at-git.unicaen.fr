<?php

namespace Application\Service\Traits;

use Application\Service\TypeHeures;
use Application\Module;
use RuntimeException;

/**
 * Description of TypeHeuresAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeHeuresAwareTrait
{
    /**
     * @var TypeHeures
     */
    private $serviceTypeHeures;





    /**
     * @param TypeHeures $serviceTypeHeures
     * @return self
     */
    public function setServiceTypeHeures( TypeHeures $serviceTypeHeures )
    {
        $this->serviceTypeHeures = $serviceTypeHeures;
        return $this;
    }



    /**
     * @return TypeHeures
     * @throws RuntimeException
     */
    public function getServiceTypeHeures()
    {
        if (empty($this->serviceTypeHeures)){
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
        $this->serviceTypeHeures = $serviceLocator->get('ApplicationTypeHeures');
        }
        return $this->serviceTypeHeures;
    }
}