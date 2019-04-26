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
     * @var string|null
     */
    protected $iParam1Libelle;

    /**
     * @var string|null
     */
    protected $iParam2Libelle;

    /**
     * @var string|null
     */
    protected $iParam3Libelle;

    /**
     * @var string|null
     */
    protected $iParam4Libelle;

    /**
     * @var string|null
     */
    protected $iParam5Libelle;

    /**
     * @var string|null
     */
    protected $vhParam1Libelle;

    /**
     * @var string|null
     */
    protected $vhParam2Libelle;

    /**
     * @var string|null
     */
    protected $vhParam3Libelle;

    /**
     * @var string|null
     */
    protected $vhParam4Libelle;

    /**
     * @var string|null
     */
    protected $vhParam5Libelle;



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
     * @return string|null
     */
    public function getIParam1Libelle()
    {
        return $this->iParam1Libelle;
    }



    /**
     * @param string|null $iParam1Libelle
     *
     * @return Formule
     */
    public function setIParam1Libelle($iParam1Libelle): Formule
    {
        $this->iParam1Libelle = $iParam1Libelle;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getIParam2Libelle()
    {
        return $this->iParam2Libelle;
    }



    /**
     * @param string|null $iParam2Libelle
     *
     * @return Formule
     */
    public function setIParam2Libelle($iParam2Libelle): Formule
    {
        $this->iParam2Libelle = $iParam2Libelle;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getIParam3Libelle()
    {
        return $this->iParam3Libelle;
    }



    /**
     * @param string|null $iParam3Libelle
     *
     * @return Formule
     */
    public function setIParam3Libelle($iParam3Libelle): Formule
    {
        $this->iParam3Libelle = $iParam3Libelle;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getIParam4Libelle()
    {
        return $this->iParam4Libelle;
    }



    /**
     * @param string|null $iParam4Libelle
     *
     * @return Formule
     */
    public function setIParam4Libelle($iParam4Libelle): Formule
    {
        $this->iParam4Libelle = $iParam4Libelle;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getIParam5Libelle()
    {
        return $this->iParam5Libelle;
    }



    /**
     * @param string|null $iParam5Libelle
     *
     * @return Formule
     */
    public function setIParam5Libelle($iParam5Libelle): Formule
    {
        $this->iParam5Libelle = $iParam5Libelle;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getVhParam1Libelle()
    {
        return $this->vhParam1Libelle;
    }



    /**
     * @param string|null $vhParam1Libelle
     *
     * @return Formule
     */
    public function setVhParam1Libelle($vhParam1Libelle): Formule
    {
        $this->vhParam1Libelle = $vhParam1Libelle;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getVhParam2Libelle()
    {
        return $this->vhParam2Libelle;
    }



    /**
     * @param string|null $vhParam2Libelle
     *
     * @return Formule
     */
    public function setVhParam2Libelle($vhParam2Libelle): Formule
    {
        $this->vhParam2Libelle = $vhParam2Libelle;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getVhParam3Libelle()
    {
        return $this->vhParam3Libelle;
    }



    /**
     * @param string|null $vhParam3Libelle
     *
     * @return Formule
     */
    public function setVhParam3Libelle($vhParam3Libelle): Formule
    {
        $this->vhParam3Libelle = $vhParam3Libelle;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getVhParam4Libelle()
    {
        return $this->vhParam4Libelle;
    }



    /**
     * @param string|null $vhParam4Libelle
     *
     * @return Formule
     */
    public function setVhParam4Libelle($vhParam4Libelle): Formule
    {
        $this->vhParam4Libelle = $vhParam4Libelle;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getVhParam5Libelle()
    {
        return $this->vhParam5Libelle;
    }



    /**
     * @param string|null $vhParam5Libelle
     *
     * @return Formule
     */
    public function setVhParam5Libelle($vhParam5Libelle): Formule
    {
        $this->vhParam5Libelle = $vhParam5Libelle;

        return $this;
    }



    /**
     * @return array
     */
    public function libellesToArray(): array
    {
        return [
            'iParam1Libelle' => $this->iParam1Libelle,
            'iParam2Libelle' => $this->iParam2Libelle,
            'iParam3Libelle' => $this->iParam3Libelle,
            'iParam4Libelle' => $this->iParam4Libelle,
            'iParam5Libelle' => $this->iParam5Libelle,
            'vhParam1Libelle' => $this->vhParam1Libelle,
            'vhParam2Libelle' => $this->vhParam2Libelle,
            'vhParam3Libelle' => $this->vhParam3Libelle,
            'vhParam4Libelle' => $this->vhParam4Libelle,
            'vhParam5Libelle' => $this->vhParam5Libelle,
        ];
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
