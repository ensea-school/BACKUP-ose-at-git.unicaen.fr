<?php

namespace Lieu\Entity\Db;

/**
 * Voirie
 */
class AdresseNumeroCompl
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $codeRh;

    /**
     * @var string
     */
    protected $libelle;



    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }



    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }



    /**
     * @return string
     */
    public function getCodeRh(): ?string
    {
        return $this->codeRh;
    }



    /**
     * @return string
     */
    public function getLibelle(): string
    {
        return $this->libelle;
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }

}
