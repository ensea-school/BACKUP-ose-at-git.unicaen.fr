<?php

namespace Application\Service\Traits;

use Application\Service\EtatVolumeHoraire;

/**
 * Description of EtatVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait EtatVolumeHoraireAwareTrait
{
    /**
     * @var EtatVolumeHoraire
     */
    private $serviceEtatVolumeHoraire;



    /**
     * @param EtatVolumeHoraire $serviceEtatVolumeHoraire
     *
     * @return self
     */
    public function setServiceEtatVolumeHoraire(EtatVolumeHoraire $serviceEtatVolumeHoraire)
    {
        $this->serviceEtatVolumeHoraire = $serviceEtatVolumeHoraire;

        return $this;
    }



    /**
     * @return EtatVolumeHoraire
     */
    public function getServiceEtatVolumeHoraire()
    {
        if (empty($this->serviceEtatVolumeHoraire)) {
            $this->serviceEtatVolumeHoraire = \Application::$container->get('ApplicationEtatVolumeHoraire');
        }

        return $this->serviceEtatVolumeHoraire;
    }
}