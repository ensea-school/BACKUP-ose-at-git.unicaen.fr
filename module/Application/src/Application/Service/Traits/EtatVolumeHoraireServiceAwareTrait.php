<?php

namespace Application\Service\Traits;

use Application\Service\EtatVolumeHoraireService;

/**
 * Description of EtatVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait EtatVolumeHoraireServiceAwareTrait
{
    /**
     * @var EtatVolumeHoraireService
     */
    private $serviceEtatVolumeHoraire;



    /**
     * @param EtatVolumeHoraireService $serviceEtatVolumeHoraire
     *
     * @return self
     */
    public function setServiceEtatVolumeHoraire(EtatVolumeHoraireService $serviceEtatVolumeHoraire)
    {
        $this->serviceEtatVolumeHoraire = $serviceEtatVolumeHoraire;

        return $this;
    }



    /**
     * @return EtatVolumeHoraireService
     */
    public function getServiceEtatVolumeHoraire()
    {
        if (empty($this->serviceEtatVolumeHoraire)) {
            $this->serviceEtatVolumeHoraire = \Application::$container->get('ApplicationEtatVolumeHoraire');
        }

        return $this->serviceEtatVolumeHoraire;
    }
}