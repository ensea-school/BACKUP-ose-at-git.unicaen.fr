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
    protected ?FormuleTestIntervenantService $serviceFormuleTestIntervenant = null;



    /**
     * @param FormuleTestIntervenantService $serviceFormuleTestIntervenant
     *
     * @return self
     */
    public function setServiceFormuleTestIntervenant( ?FormuleTestIntervenantService $serviceFormuleTestIntervenant )
    {
        $this->serviceFormuleTestIntervenant = $serviceFormuleTestIntervenant;

        return $this;
    }



    public function getServiceFormuleTestIntervenant(): ?FormuleTestIntervenantService
    {
        if (empty($this->serviceFormuleTestIntervenant)){
            $this->serviceFormuleTestIntervenant = \Application::$container->get(FormuleTestIntervenantService::class);
        }

        return $this->serviceFormuleTestIntervenant;
    }
}