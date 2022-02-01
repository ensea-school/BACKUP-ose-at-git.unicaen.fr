<?php

namespace Application\Service\Traits;

use Application\Service\LocalContextService;

/**
 * Description of LocalContextServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait LocalContextServiceAwareTrait
{
    protected ?LocalContextService $serviceLocalContext = null;



    /**
     * @param LocalContextService $serviceLocalContext
     *
     * @return self
     */
    public function setServiceLocalContext( LocalContextService $serviceLocalContext )
    {
        $this->serviceLocalContext = $serviceLocalContext;

        return $this;
    }



    public function getServiceLocalContext(): ?LocalContextService
    {
        if (empty($this->serviceLocalContext)){
            $this->serviceLocalContext = \Application::$container->get(LocalContextService::class);
        }

        return $this->serviceLocalContext;
    }
}