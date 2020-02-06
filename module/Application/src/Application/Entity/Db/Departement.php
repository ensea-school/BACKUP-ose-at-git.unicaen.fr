<?php

namespace Application\Entity\Db;


/**
 * Departement
 */
class Departement
{
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
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getCode() . " - " . $this->getLibelle();
    }



    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }



    /**
     * @param int $id
     *
     * @return Departement
     */
    public function setId(int $id): Departement
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }



    /**
     * @param string $code
     *
     * @return Departement
     */
    public function setCode(string $code): Departement
    {
        $this->code = $code;

        return $this;
    }



    /**
     * @return string
     */
    public function getLibelle(): string
    {
        return $this->libelle;
    }



    /**
     * @param string $libelle
     *
     * @return Departement
     */
    public function setLibelle(string $libelle): Departement
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return bool
     */
    public function inIleDeFrance(): bool
    {
        $intCode = (int)$this->getCode();

        return in_array($intCode, [78, 91, 92, 93, 94, 95]);
    }

}
