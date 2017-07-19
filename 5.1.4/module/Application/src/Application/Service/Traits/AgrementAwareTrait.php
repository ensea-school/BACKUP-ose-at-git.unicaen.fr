<?php

namespace Application\Service\Traits;

use Application\Service\Agrement;
use Application\Module;
use RuntimeException;

/**
 * Description of AgrementAwareTrait
 *
 * @author UnicaenCode
 */
trait AgrementAwareTrait
{
    /**
     * @var Agrement
     */
    private $serviceAgrement;





    /**
     * @param Agrement $serviceAgrement
     * @return self
     */
    public function setServiceAgrement( Agrement $serviceAgrement )
    {
        $this->serviceAgrement = $serviceAgrement;
        return $this;
    }



    /**
     * @return Agrement
     * @throws RuntimeException
     */
    public function getServiceAgrement()
    {
        if (empty($this->serviceAgrement)){
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
        $this->serviceAgrement = $serviceLocator->get('ApplicationAgrement');
        }
        return $this->serviceAgrement;
    }
}