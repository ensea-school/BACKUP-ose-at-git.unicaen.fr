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
    /**
     * @var AgrementService
     */
    private $serviceAgrement;



    /**
     * @param AgrementService $serviceAgrement
     *
     * @return self
     */
    public function setServiceAgrement(AgrementService $serviceAgrement)
    {
        $this->serviceAgrement = $serviceAgrement;

        return $this;
    }



    /**
     * @return AgrementService
     */
    public function getServiceAgrement()
    {
        if (empty($this->serviceAgrement)) {
            $this->serviceAgrement = \Application::$container->get(AgrementService::class);
        }

        return $this->serviceAgrement;
    }
}