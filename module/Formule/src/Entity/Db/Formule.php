<?php

namespace Formule\Entity\Db;


class Formule
{
    protected int $id;

    protected string $libelle;

    protected string $code;

    protected ?int $delegationAnnee = null;

    protected ?string $delegationFormule = null;

    protected bool $active = true;

    protected ?string $sqlIntervenant = null;

    protected ?string $sqlVolumeHoraire = null;

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
