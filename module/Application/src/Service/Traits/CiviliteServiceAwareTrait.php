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
    protected ?CiviliteService $serviceCivilite;



    /**
     * @param CiviliteService|null $serviceCivilite
     *
     * @return self
     */
    public function setServiceCivilite( ?CiviliteService $serviceCivilite )
    {
        $this->serviceCivilite = $serviceCivilite;

        return $this;
    }



    public function getServiceCivilite(): ?CiviliteService
    {
        if (!$this->serviceCivilite){
            $this->serviceCivilite = \Application::$container->get(CiviliteService::class);
        }

        return $this->serviceCivilite;
    }
}