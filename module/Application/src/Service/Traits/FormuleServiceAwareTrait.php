<?php

namespace Application\Service\Traits;

use Application\Service\FormuleService;

/**
 * Description of FormuleServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleServiceAwareTrait
{
    protected ?FormuleService $serviceFormule = null;



    /**
     * @param FormuleService $serviceFormule
     *
     * @return self
     */
    public function setServiceFormule( ?FormuleService $serviceFormule )
    {
        $this->serviceFormule = $serviceFormule;

        return $this;
    }



    public function getServiceFormule(): ?FormuleService
    {
        if (empty($this->serviceFormule)){
            $this->serviceFormule = \Application::$container->get(FormuleService::class);
        }

        return $this->serviceFormule;
    }
}