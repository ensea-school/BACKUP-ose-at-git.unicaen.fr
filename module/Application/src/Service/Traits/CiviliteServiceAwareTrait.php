<?php

namespace Application\Service\Traits;

use Application\Service\CiviliteService;

/**
 * Description of CiviliteServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait CiviliteServiceAwareTrait
{
    protected ?CiviliteService $serviceCivilite = null;



    /**
     * @param CiviliteService $serviceCivilite
     *
     * @return self
     */
    public function setServiceCivilite(?CiviliteService $serviceCivilite)
    {
        $this->serviceCivilite = $serviceCivilite;

        return $this;
    }



    public function getServiceCivilite(): ?CiviliteService
    {
        if (empty($this->serviceCivilite)) {
            $this->serviceCivilite = \Application::$container->get(CiviliteService::class);
        }

        return $this->serviceCivilite;
    }
}