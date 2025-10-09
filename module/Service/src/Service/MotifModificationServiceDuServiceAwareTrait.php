<?php

namespace Service\Service;

/**
 * Description of MotifModificationServiceDuServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait MotifModificationServiceDuServiceAwareTrait
{
    protected ?MotifModificationServiceDuService $serviceMotifModificationServiceDu = null;



    /**
     * @param MotifModificationServiceDuService $serviceMotifModificationServiceDu
     *
     * @return self
     */
    public function setServiceMotifModificationServiceDu(?MotifModificationServiceDuService $serviceMotifModificationServiceDu)
    {
        $this->serviceMotifModificationServiceDu = $serviceMotifModificationServiceDu;

        return $this;
    }



    public function getServiceMotifModificationServiceDu(): ?MotifModificationServiceDuService
    {
        if (empty($this->serviceMotifModificationServiceDu)) {
            $this->serviceMotifModificationServiceDu =\Unicaen\Framework\Application\Application::getInstance()->container()->get(MotifModificationServiceDuService::class);
        }

        return $this->serviceMotifModificationServiceDu;
    }
}