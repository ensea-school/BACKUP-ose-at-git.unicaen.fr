<?php

namespace Formule\Entity\Db;


class Formule
{
    protected int $id;

    protected string $libelle;

    protected string $code;

    protected ?int $delegationAnnee = null;

    protected ?string $delegationFormule = null;

    protected ?string $phpClass = null;

    protected bool $active = true;

    protected ?string $sqlIntervenant = null;
    protected ?string $sqlVolumeHoraire = null;

    protected ?string $heuresServiceFiCol = null;
    protected ?string $heuresServiceFaCol = null;
    protected ?string $heuresServiceFcCol = null;
    protected ?string $heuresServiceReferentielCol = null;
    protected ?string $heuresComplFiCol = null;
    protected ?string $heuresComplFaCol = null;
    protected ?string $heuresComplFcCol = null;
    protected ?string $heuresComplReferentielCol = null;
    protected ?string $heuresPrimesCol = null;
    protected ?string $heuresNonPayableFiCol = null;
    protected ?string $heuresNonPayableFaCol = null;
    protected ?string $heuresNonPayableFcCol = null;
    protected ?string $heuresNonPayableReferentielCol = null;

    protected ?string $iParam1Libelle = null;
    protected ?string $iParam2Libelle = null;
    protected ?string $iParam3Libelle = null;
    protected ?string $iParam4Libelle = null;
    protected ?string $iParam5Libelle = null;

    protected ?string $vhParam1Libelle = null;
    protected ?string $vhParam2Libelle = null;
    protected ?string $vhParam3Libelle = null;
    protected ?string $vhParam4Libelle = null;
    protected ?string $vhParam5Libelle = null;



    public function libellesToArray(): array
    {
        return [
            'iParam1Libelle'  => $this->iParam1Libelle,
            'iParam2Libelle'  => $this->iParam2Libelle,
            'iParam3Libelle'  => $this->iParam3Libelle,
            'iParam4Libelle'  => $this->iParam4Libelle,
            'iParam5Libelle'  => $this->iParam5Libelle,
            'vhParam1Libelle' => $this->vhParam1Libelle,
            'vhParam2Libelle' => $this->vhParam2Libelle,
            'vhParam3Libelle' => $this->vhParam3Libelle,
            'vhParam4Libelle' => $this->vhParam4Libelle,
            'vhParam5Libelle' => $this->vhParam5Libelle,
        ];
    }



    public function __toString(): string
    {
        return $this->getLibelle();
    }



    public function getId(): int
    {
        return $this->id;
    }



    public function getLibelle(): string
    {
        return $this->libelle;
    }



    public function setLibelle(string $libelle): Formule
    {
        $this->libelle = $libelle;
        return $this;
    }



    public function getCode(): string
    {
        return $this->code;
    }



    public function setCode(string $code): Formule
    {
        $this->code = $code;
        return $this;
    }



    public function getDelegationAnnee(): ?int
    {
        return $this->delegationAnnee;
    }



    public function setDelegationAnnee(?int $delegationAnnee): Formule
    {
        $this->delegationAnnee = $delegationAnnee;
        return $this;
    }



    public function getDelegationFormule(): ?string
    {
        return $this->delegationFormule;
    }



    public function setDelegationFormule(?string $delegationFormule): Formule
    {
        $this->delegationFormule = $delegationFormule;
        return $this;
    }



    public function getPhpClass(): ?string
    {
        return $this->phpClass;
    }



    public function setPhpClass(?string $phpClass): Formule
    {
        $this->phpClass = $phpClass;
        return $this;
    }



    public function isActive(): bool
    {
        return $this->active;
    }



    public function setActive(bool $active): Formule
    {
        $this->active = $active;
        return $this;
    }



    public function getSqlIntervenant(): ?string
    {
        return $this->sqlIntervenant;
    }



    public function setSqlIntervenant(?string $sqlIntervenant): Formule
    {
        $this->sqlIntervenant = $sqlIntervenant;
        return $this;
    }



    public function getSqlVolumeHoraire(): ?string
    {
        return $this->sqlVolumeHoraire;
    }



    public function setSqlVolumeHoraire(?string $sqlVolumeHoraire): Formule
    {
        $this->sqlVolumeHoraire = $sqlVolumeHoraire;
        return $this;
    }



    public function getHeuresServiceFiCol(): ?string
    {
        return $this->heuresServiceFiCol;
    }



    public function setHeuresServiceFiCol(?string $heuresServiceFiCol): Formule
    {
        $this->heuresServiceFiCol = $heuresServiceFiCol;
        return $this;
    }



    public function getHeuresServiceFaCol(): ?string
    {
        return $this->heuresServiceFaCol;
    }



    public function setHeuresServiceFaCol(?string $heuresServiceFaCol): Formule
    {
        $this->heuresServiceFaCol = $heuresServiceFaCol;
        return $this;
    }



    public function getHeuresServiceFcCol(): ?string
    {
        return $this->heuresServiceFcCol;
    }



    public function setHeuresServiceFcCol(?string $heuresServiceFcCol): Formule
    {
        $this->heuresServiceFcCol = $heuresServiceFcCol;
        return $this;
    }



    public function getHeuresServiceReferentielCol(): ?string
    {
        return $this->heuresServiceReferentielCol;
    }



    public function setHeuresServiceReferentielCol(?string $heuresServiceReferentielCol): Formule
    {
        $this->heuresServiceReferentielCol = $heuresServiceReferentielCol;
        return $this;
    }



    public function getHeuresComplFiCol(): ?string
    {
        return $this->heuresComplFiCol;
    }



    public function setHeuresComplFiCol(?string $heuresComplFiCol): Formule
    {
        $this->heuresComplFiCol = $heuresComplFiCol;
        return $this;
    }



    public function getHeuresComplFaCol(): ?string
    {
        return $this->heuresComplFaCol;
    }



    public function setHeuresComplFaCol(?string $heuresComplFaCol): Formule
    {
        $this->heuresComplFaCol = $heuresComplFaCol;
        return $this;
    }



    public function getHeuresComplFcCol(): ?string
    {
        return $this->heuresComplFcCol;
    }



    public function setHeuresComplFcCol(?string $heuresComplFcCol): Formule
    {
        $this->heuresComplFcCol = $heuresComplFcCol;
        return $this;
    }



    public function getHeuresComplReferentielCol(): ?string
    {
        return $this->heuresComplReferentielCol;
    }



    public function setHeuresComplReferentielCol(?string $heuresComplReferentielCol): Formule
    {
        $this->heuresComplReferentielCol = $heuresComplReferentielCol;
        return $this;
    }



    public function getHeuresPrimesCol(): ?string
    {
        return $this->heuresPrimesCol;
    }



    public function setHeuresPrimesCol(?string $heuresPrimesCol): Formule
    {
        $this->heuresPrimesCol = $heuresPrimesCol;
        return $this;
    }



    public function getHeuresNonPayableFiCol(): ?string
    {
        return $this->heuresNonPayableFiCol;
    }



    public function setHeuresNonPayableFiCol(?string $heuresNonPayableFiCol): Formule
    {
        $this->heuresNonPayableFiCol = $heuresNonPayableFiCol;
        return $this;
    }



    public function getHeuresNonPayableFaCol(): ?string
    {
        return $this->heuresNonPayableFaCol;
    }



    public function setHeuresNonPayableFaCol(?string $heuresNonPayableFaCol): Formule
    {
        $this->heuresNonPayableFaCol = $heuresNonPayableFaCol;
        return $this;
    }



    public function getHeuresNonPayableFcCol(): ?string
    {
        return $this->heuresNonPayableFcCol;
    }



    public function setHeuresNonPayableFcCol(?string $heuresNonPayableFcCol): Formule
    {
        $this->heuresNonPayableFcCol = $heuresNonPayableFcCol;
        return $this;
    }



    public function getHeuresNonPayableReferentielCol(): ?string
    {
        return $this->heuresNonPayableReferentielCol;
    }



    public function setHeuresNonPayableReferentielCol(?string $heuresNonPayableReferentielCol): Formule
    {
        $this->heuresNonPayableReferentielCol = $heuresNonPayableReferentielCol;
        return $this;
    }



    public function getIParam1Libelle(): ?string
    {
        return $this->iParam1Libelle;
    }



    public function setIParam1Libelle(?string $iParam1Libelle): Formule
    {
        $this->iParam1Libelle = $iParam1Libelle;
        return $this;
    }



    public function getIParam2Libelle(): ?string
    {
        return $this->iParam2Libelle;
    }



    public function setIParam2Libelle(?string $iParam2Libelle): Formule
    {
        $this->iParam2Libelle = $iParam2Libelle;
        return $this;
    }



    public function getIParam3Libelle(): ?string
    {
        return $this->iParam3Libelle;
    }



    public function setIParam3Libelle(?string $iParam3Libelle): Formule
    {
        $this->iParam3Libelle = $iParam3Libelle;
        return $this;
    }



    public function getIParam4Libelle(): ?string
    {
        return $this->iParam4Libelle;
    }



    public function setIParam4Libelle(?string $iParam4Libelle): Formule
    {
        $this->iParam4Libelle = $iParam4Libelle;
        return $this;
    }



    public function getIParam5Libelle(): ?string
    {
        return $this->iParam5Libelle;
    }



    public function setIParam5Libelle(?string $iParam5Libelle): Formule
    {
        $this->iParam5Libelle = $iParam5Libelle;
        return $this;
    }



    public function getVhParam1Libelle(): ?string
    {
        return $this->vhParam1Libelle;
    }



    public function setVhParam1Libelle(?string $vhParam1Libelle): Formule
    {
        $this->vhParam1Libelle = $vhParam1Libelle;
        return $this;
    }



    public function getVhParam2Libelle(): ?string
    {
        return $this->vhParam2Libelle;
    }



    public function setVhParam2Libelle(?string $vhParam2Libelle): Formule
    {
        $this->vhParam2Libelle = $vhParam2Libelle;
        return $this;
    }



    public function getVhParam3Libelle(): ?string
    {
        return $this->vhParam3Libelle;
    }



    public function setVhParam3Libelle(?string $vhParam3Libelle): Formule
    {
        $this->vhParam3Libelle = $vhParam3Libelle;
        return $this;
    }



    public function getVhParam4Libelle(): ?string
    {
        return $this->vhParam4Libelle;
    }



    public function setVhParam4Libelle(?string $vhParam4Libelle): Formule
    {
        $this->vhParam4Libelle = $vhParam4Libelle;
        return $this;
    }



    public function getVhParam5Libelle(): ?string
    {
        return $this->vhParam5Libelle;
    }



    public function setVhParam5Libelle(?string $vhParam5Libelle): Formule
    {
        $this->vhParam5Libelle = $vhParam5Libelle;
        return $this;
    }

}
