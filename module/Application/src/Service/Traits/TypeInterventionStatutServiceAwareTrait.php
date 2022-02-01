<?php

namespace Application\Service\Traits;

use Application\Service\TypeInterventionStatutService;

/**
 * Description of TypeInterventionStatutServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionStatutServiceAwareTrait
{
    protected ?TypeInterventionStatutService $serviceTypeInterventionStatut;



    /**
     * @param TypeInterventionStatutService|null $serviceTypeInterventionStatut
     *
     * @return self
     */
    public function setServiceTypeInterventionStatut( ?TypeInterventionStatutService $serviceTypeInterventionStatut )
    {
        $this->serviceTypeInterventionStatut = $serviceTypeInterventionStatut;

        return $this;
    }



    public function getServiceTypeInterventionStatut(): ?TypeInterventionStatutService
    {
        if (!$this->serviceTypeInterventionStatut){
            $this->serviceTypeInterventionStatut = \Application::$container->get(TypeInterventionStatutService::class);
        }

        return $this->serviceTypeInterventionStatut;
    }
}