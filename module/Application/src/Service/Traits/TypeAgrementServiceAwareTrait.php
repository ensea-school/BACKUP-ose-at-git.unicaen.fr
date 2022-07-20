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
    protected ?TypeAgrementService $serviceTypeAgrement = null;



    /**
     * @param TypeAgrementService $serviceTypeAgrement
     *
     * @return self
     */
    public function setServiceTypeAgrement(?TypeAgrementService $serviceTypeAgrement)
    {
        $this->serviceTypeAgrement = $serviceTypeAgrement;

        return $this;
    }



    public function getServiceTypeAgrement(): ?TypeAgrementService
    {
        if (empty($this->serviceTypeAgrement)) {
            $this->serviceTypeAgrement = \Application::$container->get(TypeAgrementService::class);
        }

        return $this->serviceTypeAgrement;
    }
}