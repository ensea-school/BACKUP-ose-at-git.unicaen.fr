<?php

namespace Application\Service\Traits;

use Application\Service\FormuleTestIntervenantService;

/**
 * Description of FormuleTestIntervenantServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleTestIntervenantServiceAwareTrait
{
    /**
     * @var FormuleTestIntervenantService
     */
    protected $serviceFormuleTestIntervenant;



    /**
     * @param FormuleTestIntervenantService $serviceFormuleTestIntervenant
     * @return self
     */
    public function setServiceFormuleTestIntervenant( FormuleTestIntervenantService $serviceFormuleTestIntervenant )
    {
        $this->serviceFormuleTestIntervenant = $serviceFormuleTestIntervenant;

        return $this;
    }



    /**
     * @return FormuleTestIntervenantService
     */
    public function getServiceFormuleTestIntervenant() : FormuleTestIntervenantService
    {
        if (!$this->serviceFormuleTestIntervenant){
            $this->serviceFormuleTestIntervenant = \Application::$container->get(FormuleTestIntervenantService::class);
        }

        return $this->serviceFormuleTestIntervenant;
    }
}