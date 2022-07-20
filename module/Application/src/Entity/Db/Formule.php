<?php

namespace Application\Entity\Db;


/**
 * Formule
 */
class Formule
{
    protected int     $id;

    protected string  $libelle;

    protected string  $packageName;

    protected bool    $active          = true;

    protected ?string $iParam1Libelle  = null;

    protected ?string $iParam2Libelle  = null;

    protected ?string $iParam3Libelle  = null;

    protected ?string $iParam4Libelle  = null;

    protected ?string $iParam5Libelle  = null;

    protected ?string $vhParam1Libelle = null;

    protected ?string $vhParam2Libelle = null;

    protected ?string $vhParam3Libelle = null;

    protected ?string $vhParam4Libelle = null;

    protected ?string $vhParam5Libelle = null;



    public function getId(): int
    {
        return $this->id;
    }



    public function getLibelle(): string
    {
        return $this->libelle;
    }



    public function getPackageName(): string
    {
        return $this->packageName;
    }



    public function isActive(): bool
    {
        return $this->active;
    }



    public function getIParam1Libelle(): ?string
    {
        return $this->iParam1Libelle;
    }



    public function getIParam2Libelle(): ?string
    {
        return $this->iParam2Libelle;
    }



    public function getIParam3Libelle(): ?string
    {
        return $this->iParam3Libelle;
    }



    public function getIParam4Libelle(): ?string
    {
        return $this->iParam4Libelle;
    }



    public function getIParam5Libelle(): ?string
    {
        return $this->iParam5Libelle;
    }



    public function getVhParam1Libelle(): ?string
    {
        return $this->vhParam1Libelle;
    }



    public function getVhParam2Libelle(): ?string
    {
        return $this->vhParam2Libelle;
    }



    public function getVhParam3Libelle(): ?string
    {
        return $this->vhParam3Libelle;
    }



    public function getVhParam4Libelle(): ?string
    {
        return $this->vhParam4Libelle;
    }



    public function getVhParam5Libelle(): ?string
    {
        return $this->vhParam5Libelle;
    }



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
}
