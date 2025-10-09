<?php

namespace Intervenant\Service;

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
            $this->serviceCivilite = \Unicaen\Framework\Application\Application::getInstance()->container()->get(CiviliteService::class);
        }

        return $this->serviceCivilite;
    }
}