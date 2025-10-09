<?php

namespace Agrement\Service\Traits;

use Agrement\Service\TypeAgrementService;

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
            $this->serviceTypeAgrement =\Unicaen\Framework\Application\Application::getInstance()->container()->get(TypeAgrementService::class);
        }

        return $this->serviceTypeAgrement;
    }
}