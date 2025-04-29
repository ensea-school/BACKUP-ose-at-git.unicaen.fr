<?php

namespace PieceJointe\Entity\Db;

use Administration\Interfaces\ParametreEntityInterface;
use Administration\Traits\ParametreEntityTrait;
use Intervenant\Entity\Db\StatutAwareTrait;
use PieceJointe\Entity\Db\Traits\TypePieceJointeAwareTrait;

class TypePieceJointeStatut implements ParametreEntityInterface
{
    use ParametreEntityTrait;
    use StatutAwareTrait;
    use TypePieceJointeAwareTrait;

    private float $seuilHetd = 0;

    private bool  $typeHeureHetd = false;

    private bool  $fc = false;

    private bool  $changementRIB = false;

    private bool  $nationaliteEtrangere = false;

    private int   $dureeVie = 1;

    private bool  $obligatoireHNP = false;

    private bool  $obligatoire = true;

    protected int $numRegle = 1;

    protected bool $demandeeApresRecrutement = false;



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



    /**
     * @return bool
     */
    public function isNationaliteEtrangere(): bool
    {
        return $this->nationaliteEtrangere;
    }



    /**
     * @param bool $nationaliteEtrangere
     *
     * @return TypePieceJointeStatut $this
     */
    public function setNationaliteEtrangere(bool $nationaliteEtrangere): TypePieceJointeStatut
    {
        $this->nationaliteEtrangere = $nationaliteEtrangere;

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



    public function getNumRegle(): int
    {
        return $this->numRegle;
    }



    public function setNumRegle(int $numRegle): TypePieceJointeStatut
    {
        $this->numRegle = $numRegle;

        return $this;
    }



    public function isDemandeeApresRecrutement(): bool
    {
        return $this->demandeeApresRecrutement;
    }



    public function setDemandeeApresRecrutement(bool $demandeeApresRecrutement): typePieceJointeStatut
    {
        $this->demandeeApresRecrutement = $demandeeApresRecrutement;

        return $this;
    }





    public function __toString()
    {
        $txt = $this->getObligatoire() ? 'Obl' : 'Fac';
        if ($this->getSeuilHetd()) {
            $txt .= ' >' . $this->getSeuilHetd();
        }
        if ($this->getFc()) {
            $txt .= ' FC ';
        }
        if ($this->getChangementRIB()) {
            $txt .= ' RIB';
        }
        if ($this->isNationaliteEtrangere()) {
            $txt .= ' Etr';
        }
        if ($this->getDureeVie() && $this->getDureeVie() > 1) {
            $txt .= ' ' . $this->getDureeVie() . 'ans';
        }


        return $txt;
    }



    /**
     * @return string
     */
    public function getTitle(): string
    {
        $t   = [];
        $t[] = $this->getObligatoire() ? 'Pièce obligatoire' : 'Pièce facultative';
        if ($this->getSeuilHetd()) {
            $t[] = 'À partir de ' . $this->getSeuilHetd() . ' heures';
        }
        if ($this->getFc()) {
            $t[] = 'Uniquement avec des enseignements en Formation Continue';
        }
        if ($this->getChangementRIB()) {
            $t[] = 'Uniquement si le RIB a changé';
        }
        if ($this->isNationaliteEtrangere()) {
            $t[] = 'Uniquement si nationalité étrangère';
        }
        if ($this->getDureeVie()) {
            $t[] = 'Redemander la pièce tous les ' . $this->getDureeVie() . ' an(s)';
        }

        return implode("\n", $t);
    }

}
