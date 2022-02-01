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
    protected ?FormuleTestIntervenantService $serviceFormuleTestIntervenant;



    /**
     * @param FormuleTestIntervenantService|null $serviceFormuleTestIntervenant
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
        if (!$this->serviceFormuleTestIntervenant){
            $this->serviceFormuleTestIntervenant = \Application::$container->get('FormElementManager')->get(FormuleTestIntervenantService::class);
        }

        return $this->serviceFormuleTestIntervenant;
    }
}