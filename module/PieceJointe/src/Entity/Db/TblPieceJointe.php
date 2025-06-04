<?php

namespace PieceJointe\Entity\Db;

use Application\Entity\Db\Annee;
use Intervenant\Entity\Db\Intervenant;

class TblPieceJointe
{
    private int $id;

    private bool $demandee = false;

    private bool $fournie = false;

    private bool $validee = false;

    private TypePieceJointe $typePieceJointe;

    private PieceJointe $pieceJointe;

    private Intervenant $intervenant;

    private float $seuilHetd;

    private Annee $annee;

    private bool $obligatoire;

    private bool $demandeApresRecrutement;


    public function getDemandee(): bool
    {
        return $this->demandee;
    }

    public function getFournie(): bool
    {
        return $this->fournie;
    }

    public function getValidee(): bool
    {
        return $this->validee;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTypePieceJointe(): TypePieceJointe
    {
        return $this->typePieceJointe;
    }

    public function getIntervenant(): Intervenant
    {
        return $this->intervenant;
    }

    public function getSeuilHetd(): float
    {
        return $this->seuilHetd;
    }

    public function getAnnee(): Annee
    {
        return $this->annee;
    }

    public function isObligatoire(): bool
    {
        return $this->obligatoire;
    }

    public function isDemandeApresRecrutement(): bool
    {
        return $this->demandeApresRecrutement;
    }

}
