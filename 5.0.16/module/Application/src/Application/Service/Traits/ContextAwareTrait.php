<?php

namespace Application\Service\Traits;

use Application\Service\Context;
use Application\Module;
use RuntimeException;

/**
 * Description of ContextAwareTrait
 *
 * @author UnicaenCode
 */
trait ContextAwareTrait
{
    /**
     * @var Context
     */
    private $serviceContext;



    /**
     * @param Context $serviceContext
     *
     * @return self
     */
    public function setServiceContext(Context $serviceContext)
    {
        $this->serviceContext = $serviceContext;

        return $this;
    }



    /**
     * @return Context
     * @throws RuntimeException
     */
    public function getServiceContext()
    {
        if (empty($this->serviceContext)) {
            $serviceLocator = Module::$serviceLocator;
            if (!$serviceLocator) {
                if (!method_exists($this, 'getServiceLocator')) {
                    throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
                }

                $serviceLocator = $this->getServiceLocator();
                if (method_exists($serviceLocator, 'getServiceLocator')) {
                    $serviceLocator = $serviceLocator->getServiceLocator();
                }
            }
            if (!$serviceLocator) return null;
            $this->serviceContext = $serviceLocator->get('ApplicationContext');
        }

        return $this->serviceContext;
    }
}