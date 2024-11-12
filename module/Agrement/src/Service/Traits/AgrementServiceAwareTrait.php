<?php

namespace Agrement\Service\Traits;

use Agrement\Service\AgrementService;

/**
 * Description of AgrementServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait AgrementServiceAwareTrait
{
    protected ?AgrementService $serviceAgrement = null;



    /**
     * @param AgrementService $serviceAgrement
     *
     * @return self
     */
    public function setServiceAgrement(?AgrementService $serviceAgrement)
    {
        $this->serviceAgrement = $serviceAgrement;

        return $this;
    }



    public function getServiceAgrement(): ?AgrementService
    {
        if (empty($this->serviceAgrement)) {
            $this->serviceAgrement = \AppAdmin::container()->get(AgrementService::class);
        }

        return $this->serviceAgrement;
    }
}