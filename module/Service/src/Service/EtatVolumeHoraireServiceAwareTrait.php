<?php

namespace Service\Service;

/**
 * Description of EtatVolumeHoraireServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait EtatVolumeHoraireServiceAwareTrait
{
    protected ?EtatVolumeHoraireService $serviceEtatVolumeHoraire = null;



    /**
     * @param EtatVolumeHoraireService $serviceEtatVolumeHoraire
     *
     * @return self
     */
    public function setServiceEtatVolumeHoraire(?EtatVolumeHoraireService $serviceEtatVolumeHoraire)
    {
        $this->serviceEtatVolumeHoraire = $serviceEtatVolumeHoraire;

        return $this;
    }



    public function getServiceEtatVolumeHoraire(): ?EtatVolumeHoraireService
    {
        if (empty($this->serviceEtatVolumeHoraire)) {
            $this->serviceEtatVolumeHoraire = \Framework\Application\Application::getInstance()->container()->get(EtatVolumeHoraireService::class);
        }

        return $this->serviceEtatVolumeHoraire;
    }
}