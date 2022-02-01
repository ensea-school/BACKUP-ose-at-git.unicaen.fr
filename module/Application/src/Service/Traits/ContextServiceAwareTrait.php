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
    protected ?ContextService $serviceContext = null;



    /**
     * @param ContextService|null $serviceContext
     *
     * @return self
     */
    public function setServiceContext(?ContextService $serviceContext)
    {
        $this->serviceContext = $serviceContext;

        return $this;
    }



    public function getServiceContext(): ?ContextService
    {
        if (!$this->serviceContext) {
            $this->serviceContext = \Application::$container->get(ContextService::class);
        }

        return $this->serviceContext;
    }
}