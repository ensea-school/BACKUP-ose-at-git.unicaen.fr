<?php

namespace Intervenant\Entity\Db;


/**
 * TypeIntervenant
 */
class TypeIntervenant
{
    const CODE_PERMANENT = 'P';
    const CODE_EXTERIEUR = 'E';

    protected int    $id;

    protected string $code;

    protected string $libelle;



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
}
