<?php

namespace Application\Service\Traits;

use Application\Service\TypeIntervenantService;

/**
 * Description of TypeIntervenantServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeIntervenantServiceAwareTrait
{
    protected ?TypeIntervenantService $serviceTypeIntervenant;



    /**
     * @param TypeIntervenantService|null $serviceTypeIntervenant
     *
     * @return self
     */
    public function setServiceTypeIntervenant( ?TypeIntervenantService $serviceTypeIntervenant )
    {
        $this->serviceTypeIntervenant = $serviceTypeIntervenant;

        return $this;
    }



    public function getServiceTypeIntervenant(): ?TypeIntervenantService
    {
        if (!$this->serviceTypeIntervenant){
            $this->serviceTypeIntervenant = \Application::$container->get(TypeIntervenantService::class);
        }

        return $this->serviceTypeIntervenant;
    }
}