<?php

namespace Application\Service\Traits;

use Application\Service\LocalContextService;

/**
 * Description of LocalContextAwareTrait
 *
 * @author UnicaenCode
 */
trait LocalContextServiceAwareTrait
{
    /**
     * @var LocalContextService
     */
    private $serviceLocalContext;



    /**
     * @param LocalContextService $serviceLocalContext
     *
     * @return self
     */
    public function setServiceLocalContext(LocalContextService $serviceLocalContext)
    {
        $this->serviceLocalContext = $serviceLocalContext;

        return $this;
    }



    /**
     * @return LocalContextService
     */
    public function getServiceLocalContext()
    {
        if (empty($this->serviceLocalContext)) {
            $this->serviceLocalContext = \Application::$container->get(LocalContextService::class);
        }

        return $this->serviceLocalContext;
    }
}