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

    private ?TypePieceJointe $typePieceJointe = null;

    private ?PieceJointe $pieceJointe = null;

    private ?Intervenant $intervenant = null;

    private ?Annee $annee = null;

    private bool $obligatoire = false;

    private bool $demandeApresRecrutement = false;



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



    public function getTypePieceJointe(): ?TypePieceJointe
    {
        return $this->typePieceJointe;
    }



    public function getIntervenant(): ?Intervenant
    {
        return $this->intervenant;
    }



    public function getAnnee(): ?Annee
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



    public function getPieceJointe(): ?PieceJointe
    {
        return $this->pieceJointe;
    }

}
