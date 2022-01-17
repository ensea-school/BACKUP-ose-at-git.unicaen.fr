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
            $this->serviceTypeInterventionStatut = \Application::$container->get(TypeInterventionStatutService::class);
        }

        return $this->serviceTypeInterventionStatut;
    }
}