<?php

namespace Service\Service;


/**
 * Description of TypeServiceServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeServiceServiceAwareTrait
{
    protected ?TypeServiceService $serviceTypeService = null;



    /**
     * @param TypeServiceService $serviceTypeService
     *
     * @return self
     */
    public function setServiceTypeService(?TypeServiceService $serviceTypeService)
    {
        $this->serviceTypeService = $serviceTypeService;

        return $this;
    }



    public function getServiceTypeService(): ?TypeServiceService
    {
        if (empty($this->serviceTypeService)) {
            $this->serviceTypeService = \Framework\Application\Application::getInstance()->container()->get(TypeServiceService::class);
        }

        return $this->serviceTypeService;
    }
}