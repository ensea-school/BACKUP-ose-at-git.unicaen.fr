<?php

namespace Mission\Service;


/**
 * Description of MissionTauxServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait MissionTauxValeurServiceAwareTrait
{
    protected ?MissionTauxValeurService $serviceMissionTauxValeur = null;



    /**
     * @param MissionTauxValeurService $serviceMissionTauxValeur
     *
     * @return self
     */
    public function setServiceMissionTauxValeur(?MissionTauxValeurService $serviceMissionTauxValeur)
    {
        $this->serviceMissionTauxValeur = $serviceMissionTauxValeur;

        return $this;
    }



    public function getServiceMissionTauxValeur(): ?MissionTauxValeurService
    {
        if (empty($this->serviceMissionTauxValeur)) {
            $this->serviceMissionTauxValeur = \Application::$container->get(MissionTauxValeurService::class);
        }

        return $this->serviceMissionTauxValeur;
    }
}

