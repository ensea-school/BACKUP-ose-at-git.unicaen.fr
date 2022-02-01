<?php

namespace Application\Service\Traits;

use Application\Service\TypeAgrementService;

/**
 * Description of TypeAgrementServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeAgrementServiceAwareTrait
{
    protected ?TypeAgrementService $serviceTypeAgrement;



    /**
     * @param TypeAgrementService|null $serviceTypeAgrement
     *
     * @return self
     */
    public function setServiceTypeAgrement( ?TypeAgrementService $serviceTypeAgrement )
    {
        $this->serviceTypeAgrement = $serviceTypeAgrement;

        return $this;
    }



    public function getServiceTypeAgrement(): ?TypeAgrementService
    {
        if (!$this->serviceTypeAgrement){
            $this->serviceTypeAgrement = \Application::$container->get(TypeAgrementService::class);
        }

        return $this->serviceTypeAgrement;
    }
}