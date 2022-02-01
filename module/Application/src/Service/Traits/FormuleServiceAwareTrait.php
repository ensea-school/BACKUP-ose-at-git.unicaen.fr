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
    protected ?FormuleService $serviceFormule;



    /**
     * @param FormuleService|null $serviceFormule
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
        if (!$this->serviceFormule){
            $this->serviceFormule = \Application::$container->get('FormElementManager')->get(FormuleService::class);
        }

        return $this->serviceFormule;
    }
}