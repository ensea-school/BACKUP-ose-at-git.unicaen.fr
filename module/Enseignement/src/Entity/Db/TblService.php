<?php

namespace Enseignement\Entity\Db;

use Application\Entity\Db\Annee;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\Traits\AnneeAwareTrait;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\ServiceAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;

class TblService
{
    use StructureAwareTrait;
    use ServiceAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use IntervenantAwareTrait;
    use AnneeAwareTrait;

    private int   $id;

    private bool  $actif                    = false;

    private bool  $hasHeuresMauvaisePeriode = false;

    private int   $nbvh                     = 0;

    private float $heures                   = 0;

    private int   $valide                   = 0;



    public function getId(): int
    {
        return $this->id;
    }



    public function getActif(): bool
    {
        return $this->actif;
    }



    public function getHasHeuresMauvaisePeriode(): bool
    {
        return $this->hasHeuresMauvaisePeriode;
    }



    public function getNbvh(): int
    {
        return $this->nbvh;
    }



    public function getHeures(): float|int
    {
        return $this->heures;
    }



    public function getValide(): int
    {
        return $this->valide;
    }

}

