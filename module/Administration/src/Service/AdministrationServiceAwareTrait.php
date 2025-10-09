<?php

namespace Administration\Service;


/**
 * Description of AdministrationServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait AdministrationServiceAwareTrait
{
    protected ?AdministrationService $serviceAdministration = null;



    /**
     * @param AdministrationService $serviceAdministration
     *
     * @return self
     */
    public function setServiceAdministration(?AdministrationService $serviceAdministration)
    {
        $this->serviceAdministration = $serviceAdministration;

        return $this;
    }



    public function getServiceAdministration(): ?AdministrationService
    {
        if (empty($this->serviceAdministration)) {
            $this->serviceAdministration = \Unicaen\Framework\Application\Application::getInstance()->container()->get(AdministrationService::class);
        }

        return $this->serviceAdministration;
    }
}