<?php

namespace Application\Service\Traits;

use Application\Service\EtatVolumeHoraireService;

/**
 * Description of EtatVolumeHoraireServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait EtatVolumeHoraireServiceAwareTrait
{
    protected ?EtatVolumeHoraireService $serviceEtatVolumeHoraire;



    /**
     * @param EtatVolumeHoraireService|null $serviceEtatVolumeHoraire
     *
     * @return self
     */
    public function setServiceEtatVolumeHoraire( ?EtatVolumeHoraireService $serviceEtatVolumeHoraire )
    {
        $this->serviceEtatVolumeHoraire = $serviceEtatVolumeHoraire;

        return $this;
    }



    public function getServiceEtatVolumeHoraire(): ?EtatVolumeHoraireService
    {
        if (!$this->serviceEtatVolumeHoraire){
            $this->serviceEtatVolumeHoraire = \Application::$container->get(EtatVolumeHoraireService::class);
        }

        return $this->serviceEtatVolumeHoraire;
    }
}