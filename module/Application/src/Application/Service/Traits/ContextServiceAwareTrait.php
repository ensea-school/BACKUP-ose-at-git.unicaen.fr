<?php

namespace Application\Service\Traits;

use Application\Service\ContextService;
use Application\Module;
use RuntimeException;

/**
 * Description of ContextServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ContextServiceAwareTrait
{
    /**
     * @var ContextService
     */
    private $serviceContext;



    /**
     * @param ContextService $serviceContext
     *
     * @return self
     */
    public function setServiceContext(ContextService $serviceContext)
    {
        $this->serviceContext = $serviceContext;

        return $this;
    }



    /**
     * @return ContextService
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
            $this->serviceContext = $serviceLocator->get(ContextService::class);
        }

        return $this->serviceContext;
    }
}