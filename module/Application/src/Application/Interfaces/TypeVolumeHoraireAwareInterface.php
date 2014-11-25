<?php

namespace Application\Interfaces;

use Application\Entity\Db\TypeVolumeHoraire;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface TypeVolumeHoraireAwareInterface
{

    /**
     * Spécifie le type de volume horaire concerné.
     *
     * @param TypeVolumeHoraire $typeVolumeHoraire le type de volume horaire concerné
     * @return self
     */
    public function setTypeVolumeHoraire(TypeVolumeHoraire $typeVolumeHoraire = null);

    /**
     * Retourne le type de volume horaire concerné.
     *
     * @return TypeVolumeHoraire
     */
    public function getTypeVolumeHoraire();
}