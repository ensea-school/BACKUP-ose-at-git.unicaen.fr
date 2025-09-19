<?php

namespace OffreFormation\Service\Traits;

use OffreFormation\Service\TypeInterventionStatutService;

/**
 * Description of TypeInterventionStatutAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionStatutAwareTrait
{
    /**
     * @var TypeInterventionStatutService
     */
    private $serviceTypeInterventionStatut;



    /**
     * @param TypeInterventionStatutService $serviceTypeInterventionStatut
     *
     * @return self
     */
    public function setServiceTypeInterventionStatut(TypeInterventionStatutService $serviceTypeInterventionStatut)
    {
        $this->serviceTypeInterventionStatut = $serviceTypeInterventionStatut;

        return $this;
    }



    /**
     * @return TypeInterventionStatutService
     */
    public function getServiceTypeInterventionStatut()
    {
        if (empty($this->serviceTypeInterventionStatut)) {
            $this->serviceTypeInterventionStatut = \Framework\Application\Application::getInstance()->container()->get(TypeInterventionStatutService::class);
        }

        return $this->serviceTypeInterventionStatut;
    }
}