<?php

namespace Application\Service\Traits;

use Application\Service\AgrementService;

/**
 * Description of AgrementServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait AgrementServiceAwareTrait
{
    protected ?AgrementService $serviceAgrement;



    /**
     * @param AgrementService|null $serviceAgrement
     *
     * @return self
     */
    public function setServiceAgrement( ?AgrementService $serviceAgrement )
    {
        $this->serviceAgrement = $serviceAgrement;

        return $this;
    }



    public function getServiceAgrement(): ?AgrementService
    {
        if (!$this->serviceAgrement){
            $this->serviceAgrement = \Application::$container->get(AgrementService::class);
        }

        return $this->serviceAgrement;
    }
}