<?php

namespace OffreFormation\Service\Traits;

use OffreFormation\Service\TypeInterventionStatutService;

/**
 * Description of TypeInterventionStatutServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionStatutServiceAwareTrait
{
    protected ?TypeInterventionStatutService $serviceTypeInterventionStatut = null;



    /**
     * @param TypeInterventionStatutService $serviceTypeInterventionStatut
     *
     * @return self
     */
    public function setServiceTypeInterventionStatut(?TypeInterventionStatutService $serviceTypeInterventionStatut)
    {
        $this->serviceTypeInterventionStatut = $serviceTypeInterventionStatut;

        return $this;
    }



    public function getServiceTypeInterventionStatut(): ?TypeInterventionStatutService
    {
        if (empty($this->serviceTypeInterventionStatut)) {
            $this->serviceTypeInterventionStatut = \Unicaen\Framework\Application\Application::getInstance()->container()->get(TypeInterventionStatutService::class);
        }

        return $this->serviceTypeInterventionStatut;
    }
}