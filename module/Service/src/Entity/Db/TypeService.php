<?php

namespace Service\Entity\Db;

class TypeService
{

    const CODE_ENSEIGNEMENT   = 'ENS';
    const CODE_REFERENTIEL = 'REF';
    const CODE_MISSION = 'MIS';

    const CODES = [
        self::CODE_ENSEIGNEMENT,
        self::CODE_REFERENTIEL,
        self::CODE_MISSION,
    ];

    private int    $id;

    private string $code;

    private string $libelle;




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


    public function __toString(): string
    {
        return $this->getLibelle();
    }



    public function isEnseignement(): bool
    {
        return self::CODE_ENSEIGNEMENT === $this->getCode();
    }



    public function isReferentiel(): bool
    {
        return self::CODE_REFERENTIEL === $this->getCode();
    }

    public function isMission(): bool
    {
        return self::CODE_MISSION === $this->getCode();
    }

}
