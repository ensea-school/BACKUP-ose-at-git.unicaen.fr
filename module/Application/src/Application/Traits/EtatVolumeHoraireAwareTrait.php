<?php

namespace Application\Traits;

use Application\Entity\Db\EtatVolumeHoraire;

/**
 * Description of EtatVolumeHoraireAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait EtatVolumeHoraireAwareTrait
{
    /**
     * @var EtatVolumeHoraire
     */
    protected $etatVolumeHoraire;

    /**
     * Spécifie l'état du volume horaire concerné.
     *
     * @param EtatVolumeHoraire $etatVolumeHoraire l'état du volume horaire concerné
     */
    public function setEtatVolumeHoraire(EtatVolumeHoraire $etatVolumeHoraire = null)
    {
        $this->etatVolumeHoraire = $etatVolumeHoraire;

        return $this;
    }

    /**
     * Retourne l'état du volume horaire concerné.
     *
     * @return EtatVolumeHoraire
     */
    public function getEtatVolumeHoraire()
    {
        return $this->etatVolumeHoraire;
    }
}