<?php

namespace Application\Entity\Db;


/**
 * Formule
 */
class Formule
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var string
     */
    protected $packageName;

    /**
     * @var string
     */
    protected $procedureName;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }



    /**
     * @param string $libelle
     *
     * @return Formule
     */
    public function setLibelle(string $libelle): Formule
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return string
     */
    public function getPackageName()
    {
        return $this->packageName;
    }



    /**
     * @param string $packageName
     *
     * @return Formule
     */
    public function setPackageName(string $packageName): Formule
    {
        $this->packageName = $packageName;

        return $this;
    }



    /**
     * @return string
     */
    public function getProcedureName()
    {
        return $this->procedureName;
    }



    /**
     * @param string $procedureName
     *
     * @return Formule
     */
    public function setProcedureName(string $procedureName): Formule
    {
        $this->procedureName = $procedureName;

        return $this;
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
