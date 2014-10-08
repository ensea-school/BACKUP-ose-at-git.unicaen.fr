<?php

namespace Application\Traits;

use Application\Entity\Db\TypeVolumeHoraire;

/**
 * Description of TypeVolumeHoraireAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait TypeVolumeHoraireAwareTrait
{
    /**
     * @var TypeVolumeHoraire
     */
    protected $typeVolumeHoraire;

    /**
     * Spécifie le type de volume horaire concerné.
     *
     * @param TypeVolumeHoraire $typeVolumeHoraire Type de rôle concerné
     */
    public function setTypeVolumeHoraire(TypeVolumeHoraire $typeVolumeHoraire = null)
    {
        $this->typeVolumeHoraire = $typeVolumeHoraire;

        return $this;
    }

    /**
     * Retourne le type de volume horaire concerné.
     *
     * @return TypeVolumeHoraire
     */
    public function getTypeVolumeHoraire()
    {
        return $this->typeVolumeHoraire;
    }
}