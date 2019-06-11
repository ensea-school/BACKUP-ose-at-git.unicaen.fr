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
    /**
     * @var CiviliteService
     */
    private $serviceCivilite;



    /**
     * @param CiviliteService $serviceCivilite
     *
     * @return self
     */
    public function setServiceCivilite(CiviliteService $serviceCivilite)
    {
        $this->serviceCivilite = $serviceCivilite;

        return $this;
    }



    /**
     * @return CiviliteService
     */
    public function getServiceCivilite()
    {
        if (empty($this->serviceCivilite)) {
            $this->serviceCivilite = \Application::$container->get(CiviliteService::class);
        }

        return $this->serviceCivilite;
    }
}