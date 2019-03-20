<?php

namespace Application\Service\Traits;

use Application\Service\MotifModificationServiceDuService;

/**
 * Description of MotifModificationServiceDuAwareTrait
 *
 * @author UnicaenCode
 */
trait MotifModificationServiceDuServiceAwareTrait
{
    /**
     * @var MotifModificationServiceDuService
     */
    private $serviceMotifModificationServiceDu;



    /**
     * @param MotifModificationServiceDuService $serviceMotifModificationServiceDu
     *
     * @return self
     */
    public function setServiceMotifModificationServiceDu(MotifModificationServiceDuService $serviceMotifModificationServiceDu)
    {
        $this->serviceMotifModificationServiceDu = $serviceMotifModificationServiceDu;

        return $this;
    }



    /**
     * @return MotifModificationServiceDuService
     */
    public function getServiceMotifModificationServiceDu()
    {
        if (empty($this->serviceMotifModificationServiceDu)) {
            $this->serviceMotifModificationServiceDu = \Application::$container->get(MotifModificationServiceDuService::class);
        }

        return $this->serviceMotifModificationServiceDu;
    }
}