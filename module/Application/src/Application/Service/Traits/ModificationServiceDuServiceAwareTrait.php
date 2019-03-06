<?php

namespace Application\Service\Traits;

use Application\Service\ModificationServiceDuService;

/**
 * Description of ModificationServiceDuAwareTrait
 *
 * @author UnicaenCode
 */
trait ModificationServiceDuServiceAwareTrait
{
    /**
     * @var ModificationServiceDuService
     */
    private $serviceModificationServiceDu;



    /**
     * @param ModificationServiceDuService $serviceModificationServiceDu
     *
     * @return self
     */
    public function setServiceModificationServiceDu(ModificationServiceDuService $serviceModificationServiceDu)
    {
        $this->serviceModificationServiceDu = $serviceModificationServiceDu;

        return $this;
    }



    /**
     * @return ModificationServiceDuService
     */
    public function getServiceModificationServiceDu()
    {
        if (empty($this->serviceModificationServiceDu)) {
            $this->serviceModificationServiceDu = \Application::$container->get(ModificationServiceDuService::class);
        }

        return $this->serviceModificationServiceDu;
    }
}