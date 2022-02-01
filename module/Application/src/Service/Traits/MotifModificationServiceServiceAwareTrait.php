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
    protected ?MotifModificationServiceService $serviceMotifModificationService;



    /**
     * @param MotifModificationServiceService|null $serviceMotifModificationService
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
        if (!$this->serviceMotifModificationService){
            $this->serviceMotifModificationService = \Application::$container->get(MotifModificationServiceService::class);
        }

        return $this->serviceMotifModificationService;
    }
}