<?php

namespace Application\Service\Traits;

use Application\Service\ModificationServiceDuService;

/**
 * Description of ModificationServiceDuServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ModificationServiceDuServiceAwareTrait
{
    protected ?ModificationServiceDuService $serviceModificationServiceDu;



    /**
     * @param ModificationServiceDuService|null $serviceModificationServiceDu
     *
     * @return self
     */
    public function setServiceModificationServiceDu( ?ModificationServiceDuService $serviceModificationServiceDu )
    {
        $this->serviceModificationServiceDu = $serviceModificationServiceDu;

        return $this;
    }



    public function getServiceModificationServiceDu(): ?ModificationServiceDuService
    {
        if (!$this->serviceModificationServiceDu){
            $this->serviceModificationServiceDu = \Application::$container->get(ModificationServiceDuService::class);
        }

        return $this->serviceModificationServiceDu;
    }
}