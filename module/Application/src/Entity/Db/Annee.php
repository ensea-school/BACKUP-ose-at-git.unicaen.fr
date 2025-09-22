<?php

namespace Application\Entity\Db;

/**
 * Annee
 */
class Annee
{
    const MIN = 1950;
    const MIN_DATA = 2010;
    const MAX = 2099;

    /**
     * @var \DateTime
     */
    protected $dateDebut;

    /**
     * @var \DateTime
     */
    protected $dateFin;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var boolean
     */
    protected $active;



    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Annee
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }



    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }



    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Annee
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }



    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }



    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Annee
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }



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
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }



    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }



    /**
     * @param boolean $active
     *
     * @return Annee
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }



    /**
     * @return array
     * @since PHP 5.6.0
     * This method is called by var_dump() when dumping an object to get the properties that should be shown.
     * If the method isn't defined on an object, then all public, protected and private properties will be shown.
     *
     * @link  http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.debuginfo
     */
    public function __debugInfo()
    {
        return [
            'libelle' => $this->libelle,
        ];
    }

}
