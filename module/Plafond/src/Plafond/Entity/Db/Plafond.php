<?php

namespace Plafond\Entity\Db;

/**
 * Plafond
 */
class Plafond
{
    use PlafondPerimetreAwareTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var string
     */
    protected $requete;



    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }



    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }



    /**
     * @param string $code
     *
     * @return Plafond
     */
    public function setCode($code): Plafond
    {
        $this->code = $code;

        return $this;
    }



    /**
     * @return string
     */
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }



    /**
     * @param string $libelle
     *
     * @return Plafond
     */
    public function setLibelle($libelle): Plafond
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getRequete(): ?string
    {
        return $this->requete;
    }



    /**
     * @param string $requete
     *
     * @return Plafond
     */
    public function setRequete(string $requete): Plafond
    {
        $this->requete = $requete;

        return $this;
    }



    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString(): string
    {
        return $this->getLibelle();
    }

}
