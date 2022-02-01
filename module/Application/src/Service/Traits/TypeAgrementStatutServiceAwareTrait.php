<?php

namespace Application\Service\Traits;

use Application\Service\TypeAgrementStatutService;

/**
 * Description of TypeAgrementStatutServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeAgrementStatutServiceAwareTrait
{
    protected ?TypeAgrementStatutService $serviceTypeAgrementStatut;



    /**
     * @param TypeAgrementStatutService|null $serviceTypeAgrementStatut
     *
     * @return self
     */
    public function setServiceTypeAgrementStatut( ?TypeAgrementStatutService $serviceTypeAgrementStatut )
    {
        $this->serviceTypeAgrementStatut = $serviceTypeAgrementStatut;

        return $this;
    }



    public function getServiceTypeAgrementStatut(): ?TypeAgrementStatutService
    {
        if (!$this->serviceTypeAgrementStatut){
            $this->serviceTypeAgrementStatut = \Application::$container->get(TypeAgrementStatutService::class);
        }

        return $this->serviceTypeAgrementStatut;
    }
}