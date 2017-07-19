<?php

namespace Application\Service\Traits;

use Application\Service\LocalContext;
use Application\Module;
use RuntimeException;

/**
 * Description of LocalContextAwareTrait
 *
 * @author UnicaenCode
 */
trait LocalContextAwareTrait
{
    /**
     * @var LocalContext
     */
    private $serviceLocalContext;





    /**
     * @param LocalContext $serviceLocalContext
     * @return self
     */
    public function setServiceLocalContext( LocalContext $serviceLocalContext )
    {
        $this->serviceLocalContext = $serviceLocalContext;
        return $this;
    }



    /**
     * @return LocalContext
     * @throws RuntimeException
     */
    public function getServiceLocalContext()
    {
        if (empty($this->serviceLocalContext)){
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
        $this->serviceLocalContext = $serviceLocator->get('ApplicationLocalContext');
        }
        return $this->serviceLocalContext;
    }
}