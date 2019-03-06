<?php

namespace Application\Service\Traits;

use Application\Service\ContextService;


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
            $this->serviceContext = \Application::$container->get(ContextService::class);
        }

        return $this->serviceContext;
    }
}