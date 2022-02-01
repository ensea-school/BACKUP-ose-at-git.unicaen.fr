<?php

namespace Application\Processus\Traits;

use Application\Processus\ServiceProcessus;

/**
 * Description of ServiceProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceProcessusAwareTrait
{
    protected ?ServiceProcessus $processusService = null;



    /**
     * @param ServiceProcessus $processusService
     *
     * @return self
     */
    public function setProcessusService( ServiceProcessus $processusService )
    {
        $this->processusService = $processusService;

        return $this;
    }



    public function getProcessusService(): ?ServiceProcessus
    {
        if (empty($this->processusService)){
            $this->processusService = \Application::$container->get(ServiceProcessus::class);
        }

        return $this->processusService;
    }
}