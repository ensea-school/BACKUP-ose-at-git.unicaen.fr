<?php

namespace Application\Service\Traits;

use Application\Service\TypeAgrementStatutService;

/**
 * Description of TypeAgrementStatutAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeAgrementStatutServiceAwareTrait
{
    /**
     * @var TypeAgrementStatutService
     */
    private $serviceTypeAgrementStatut;



    /**
     * @param TypeAgrementStatutService $serviceTypeAgrementStatut
     *
     * @return self
     */
    public function setServiceTypeAgrementStatut(TypeAgrementStatutService $serviceTypeAgrementStatut)
    {
        $this->serviceTypeAgrementStatut = $serviceTypeAgrementStatut;

        return $this;
    }



    /**
     * @return TypeAgrementStatutService
     */
    public function getServiceTypeAgrementStatut()
    {
        if (empty($this->serviceTypeAgrementStatut)) {
            $this->serviceTypeAgrementStatut = \Application::$container->get(TypeAgrementStatutService::class);
        }

        return $this->serviceTypeAgrementStatut;
    }
}