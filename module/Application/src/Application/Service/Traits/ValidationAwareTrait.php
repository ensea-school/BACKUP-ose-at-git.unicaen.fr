<?php

namespace Application\Service\Traits;

use Application\Service\Validation;
use Application\Module;
use RuntimeException;

/**
 * Description of ValidationAwareTrait
 *
 * @author UnicaenCode
 */
trait ValidationAwareTrait
{
    /**
     * @var Validation
     */
    private $serviceValidation;





    /**
     * @param Validation $serviceValidation
     * @return self
     */
    public function setServiceValidation( Validation $serviceValidation )
    {
        $this->serviceValidation = $serviceValidation;
        return $this;
    }



    /**
     * @return Validation
     * @throws RuntimeException
     */
    public function getServiceValidation()
    {
        if (empty($this->serviceValidation)){
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
        $this->serviceValidation = $serviceLocator->get('ApplicationValidation');
        }
        return $this->serviceValidation;
    }
}