<?php

namespace Application\Service\Traits;

use Application\Service\MotifModificationServiceDuService;

/**
 * Description of MotifModificationServiceDuServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait MotifModificationServiceDuServiceAwareTrait
{
    protected ?MotifModificationServiceDuService $serviceMotifModificationServiceDu;



    /**
     * @param MotifModificationServiceDuService|null $serviceMotifModificationServiceDu
     *
     * @return self
     */
    public function setServiceMotifModificationServiceDu( ?MotifModificationServiceDuService $serviceMotifModificationServiceDu )
    {
        $this->serviceMotifModificationServiceDu = $serviceMotifModificationServiceDu;

        return $this;
    }



    public function getServiceMotifModificationServiceDu(): ?MotifModificationServiceDuService
    {
        if (!$this->serviceMotifModificationServiceDu){
            $this->serviceMotifModificationServiceDu = \Application::$container->get(MotifModificationServiceDuService::class);
        }

        return $this->serviceMotifModificationServiceDu;
    }
}