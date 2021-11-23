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
    /**
     * @var FormuleService
     */
    protected $serviceFormule;



    /**
     * @param FormuleService $serviceFormule
     * @return self
     */
    public function setServiceFormule( FormuleService $serviceFormule )
    {
        $this->serviceFormule = $serviceFormule;

        return $this;
    }



    /**
     * @return FormuleService
     */
    public function getServiceFormule() : FormuleService
    {
        if (!$this->serviceFormule){
            $this->serviceFormule = \Application::$container->get(FormuleService::class);
        }

        return $this->serviceFormule;
    }
}