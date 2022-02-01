<?php

namespace Application\Service\Traits;

use Application\Service\MotifModificationServiceService;

/**
 * Description of MotifModificationServiceServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait MotifModificationServiceServiceAwareTrait
{
    protected ?MotifModificationServiceService $serviceMotifModificationService = null;



    /**
     * @param MotifModificationServiceService $serviceMotifModificationService
     *
     * @return self
     */
    public function setServiceMotifModificationService( ?MotifModificationServiceService $serviceMotifModificationService )
    {
        $this->serviceMotifModificationService = $serviceMotifModificationService;

        return $this;
    }



    public function getServiceMotifModificationService(): ?MotifModificationServiceService
    {
        if (empty($this->serviceMotifModificationService)){
            $this->serviceMotifModificationService = \Application::$container->get(MotifModificationServiceService::class);
        }

        return $this->serviceMotifModificationService;
    }
}