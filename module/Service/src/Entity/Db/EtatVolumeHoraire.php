<?php

namespace Service\Entity\Db;

/**
 * EtatVolumeHoraire
 */
class EtatVolumeHoraire
{
    const CODE_SAISI         = 'saisi';
    const CODE_VALIDE        = 'valide';
    const CODE_CONTRAT_EDITE = 'contrat-edite';
    const CODE_CONTRAT_SIGNE = 'contrat-signe';

    const ORDRE_SAISI = 1;

    private int    $id;

    private string $code;

    private string $libelle;

    private int    $ordre;



    public function getId(): int
    {
        return $this->id;
    }



    public function getCode(): string
    {
        return $this->code;
    }



    public function getLibelle(): string
    {
        return $this->libelle;
    }



    public function getOrdre(): int
    {
        return $this->ordre;
    }



    public function isSaisi(): bool
    {
        return $this->getCode() == self::CODE_SAISI;
    }



    public function isValide(): bool
    {
        return $this->getCode() == self::CODE_VALIDE;
    }



    public function isContratEdite(): bool
    {
        return $this->getCode() == self::CODE_CONTRAT_EDITE;
    }



    public function isContratSigne(): bool
    {
        return $this->getCode() == self::CODE_CONTRAT_SIGNE;
    }



    public function __toString(): string
    {
        return $this->getLibelle();
    }
}
