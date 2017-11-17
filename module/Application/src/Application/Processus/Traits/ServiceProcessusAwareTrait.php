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
    /**
     * @var ServiceProcessus
     */
    private $processusService;



    /**
     * @param ServiceProcessus $processusService
     *
     * @return self
     */
    public function setProcessusService(ServiceProcessus $processusService)
    {
        $this->processusService = $processusService;

        return $this;
    }



    /**
     * @return ServiceProcessus
     */
    public function getProcessusService()
    {
        if (empty($this->processusService)) {
            $this->processusService = \Application::$container->get('processusService');
        }

        return $this->processusService;
    }
}