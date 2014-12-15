<?php

namespace Application\Interfaces;

use Application\Entity\Db\EtatVolumeHoraire;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface EtatVolumeHoraireAwareInterface
{

    /**
     * Spécifie l'état du volume horaire concerné.
     *
     * @param EtatVolumeHoraire $etatVolumeHoraire l'état du volume horaire concerné
     * @return self
     */
    public function setEtatVolumeHoraire(EtatVolumeHoraire $etatVolumeHoraire = null);

    /**
     * Retourne l'état du volume horaire concerné.
     *
     * @return EtatVolumeHoraire
     */
    public function getEtatVolumeHoraire();
}