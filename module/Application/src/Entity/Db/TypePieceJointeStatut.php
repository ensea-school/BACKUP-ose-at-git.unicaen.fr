<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\TypePieceJointeAwareTrait;
use Application\Interfaces\ParametreEntityInterface;
use Application\Traits\ParametreEntityTrait;
use Intervenant\Entity\Db\StatutAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;


class TypePieceJointeStatut implements ParametreEntityInterface
{
    use ParametreEntityTrait;
    use StatutAwareTrait;
    use TypePieceJointeAwareTrait;

    private float $seuilHetd      = 0;

    private bool  $typeHeureHetd  = false;

    private bool  $fc             = false;

    private bool  $changementRIB  = false;

    private int   $dureeVie       = 1;

    private bool  $obligatoireHNP = false;

    private bool  $obligatoire    = true;



    public function getSeuilHetd(): float|int
    {
        return $this->seuilHetd;
    }



    public function setSeuilHetd(float|int $seuilHetd): TypePieceJointeStatut
    {
        $this->seuilHetd = $seuilHetd;

        return $this;
    }



    public function getTypeHeureHetd(): bool
    {
        return $this->typeHeureHetd;
    }



    public function setTypeHeureHetd(bool $typeHeureHetd): TypePieceJointeStatut
    {
        $this->typeHeureHetd = $typeHeureHetd;

        return $this;
    }



    public function getFc(): bool
    {
        return $this->fc;
    }



    public function setFc(bool $fc): TypePieceJointeStatut
    {
        $this->fc = $fc;

        return $this;
    }



    public function getChangementRIB(): bool
    {
        return $this->changementRIB;
    }



    public function setChangementRIB(bool $changementRIB): TypePieceJointeStatut
    {
        $this->changementRIB = $changementRIB;

        return $this;
    }



    public function getDureeVie(): int
    {
        return $this->dureeVie;
    }



    public function setDureeVie(int $dureeVie): TypePieceJointeStatut
    {
        $this->dureeVie = $dureeVie;

        return $this;
    }



    public function getObligatoireHNP(): bool
    {
        return $this->obligatoireHNP;
    }



    public function setObligatoireHNP(bool $obligatoireHNP): TypePieceJointeStatut
    {
        $this->obligatoireHNP = $obligatoireHNP;

        return $this;
    }



    public function getObligatoire(): bool
    {
        return $this->obligatoire;
    }



    public function setObligatoire(bool $obligatoire): TypePieceJointeStatut
    {
        $this->obligatoire = $obligatoire;

        return $this;
    }



    public function __toString()
    {
        $txt = $this->getObligatoire() ? 'Obl' : 'Fac';
        if ($this->getSeuilHetd()) $txt .= ' >' . $this->getSeuilHetd();
        if ($this->getFc()) $txt .= ' FC ';
        if ($this->getChangementRIB()) $txt .= ' RIB';
        if ($this->getDureeVie() && $this->getDureeVie() > 1) $txt .= ' ' . $this->getDureeVie() . 'ans';


        return $txt;
    }



    /**
     * @return string
     */
    public function getTitle(): string
    {
        $t   = [];
        $t[] = $this->getObligatoire() ? 'Pièce obligatoire' : 'Pièce facultative';
        if ($this->getSeuilHetd()) $t[] = 'À partir de ' . $this->getSeuilHetd() . ' heures';
        if ($this->getFc()) $t[] = 'Uniquement avec des enseignements en Formation Continue';
        if ($this->getChangementRIB()) $t[] = 'Uniquement si le RIB a changé';
        if ($this->getDureeVie()) $t[] = 'Redemander la pièce tous les ' . $this->getDureeVie();

        return implode("\n", $t);
    }

}
