<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Periode
 */
class Periode implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;
    const SEMESTRE_1 = 'S1';
    const SEMESTRE_2 = 'S2';

    /**
     * @var integer
     */
    protected $ordre;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var boolean
     */
    protected $enseignement;

    /**
     * @var string
     */
    protected $libelleCourt;

    /**
     * @var string
     */
    protected $libelleLong;

    /**
     * @var boolean
     */
    protected $paiement;

    /**
     * moisOriginePaiement
     *
     * @var integer
     */
    protected $moisOriginePaiement;

    /**
     * numeroMoisPaiement
     *
     * @var integer
     */
    protected $numeroMoisPaiement;

    /**
     * miseEnPaiementIntervenantStructure
     *
     * @var MiseEnPaiementIntervenantStructure
     */
    protected $miseEnPaiementIntervenantStructure;



    public function getCode()
    {
        return $this->code;
    }



    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }



    /**
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return Periode
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }



    /**
     * Get ordre
     *
     * @return integer
     */
    public function getOrdre()
    {
        return $this->ordre;
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
     * Set enseignement
     *
     * @param boolean $enseignement
     *
     * @return Periode
     */
    public function setEnseignement($enseignement)
    {
        $this->enseignement = $enseignement;

        return $this;
    }



    /**
     * Get enseignement
     *
     * @return boolean
     */
    public function getEnseignement()
    {
        return $this->enseignement;
    }



    /**
     * Set libelleCourt
     *
     * @param string $libelleCourt
     *
     * @return Periode
     */
    public function setLibelleCourt($libelleCourt)
    {
        $this->libelleCourt = $libelleCourt;

        return $this;
    }



    /**
     * Get libelleCourt
     *
     * @return string
     */
    public function getLibelleCourt()
    {
        return $this->libelleCourt;
    }



    /**
     * Set libelleLong
     *
     * @param string $libelleLong
     *
     * @return Periode
     */
    public function setLibelleLong($libelleLong)
    {
        $this->libelleLong = $libelleLong;

        return $this;
    }



    /**
     * Get libelleLong
     *
     * @return string
     */
    public function getLibelleLong()
    {
        return $this->libelleLong;
    }



    /**
     * Set paiement
     *
     * @param boolean $paiement
     *
     * @return Periode
     */
    public function setPaiement($paiement)
    {
        $this->paiement = $paiement;

        return $this;
    }



    /**
     * Get paiement
     *
     * @return boolean
     */
    public function getPaiement()
    {
        return $this->paiement;
    }



    /**
     * Set moisOriginePaiement
     *
     * @param boolean $moisOriginePaiement
     *
     * @return Periode
     */
    public function setMoisOriginePaiement($moisOriginePaiement)
    {
        $this->moisOriginePaiement = $moisOriginePaiement;

        return $this;
    }



    /**
     * Get moisOriginePaiement
     *
     * @return boolean
     */
    public function getMoisOriginePaiement()
    {
        return $this->moisOriginePaiement;
    }



    /**
     * Set numeroMoisPaiement
     *
     * @param boolean $numeroMoisPaiement
     *
     * @return Periode
     */
    public function setNumeroMoisPaiement($numeroMoisPaiement)
    {
        $this->numeroMoisPaiement = $numeroMoisPaiement;

        return $this;
    }



    /**
     * Get numeroMoisPaiement
     *
     * @return boolean
     */
    public function getNumeroMoisPaiement()
    {
        return $this->numeroMoisPaiement;
    }



    /**
     * Retourne la date de paiement de la période
     *
     * @param Annee $annee
     *
     * @return \DateTime
     */
    public function getDatePaiement(Annee $annee)
    {
        if (null == $this->getNumeroMoisPaiement()) return null;
        $year  = $annee->getId();
        $month = $this->getNumeroMoisPaiement();
        $day   = 1;
        if ($month < 9) $year++;
        $a_date = date("Y-m-t", mktime(0, 0, 0, $month, $day, $year));
        $date   = \DateTime::createFromFormat('Y-m-d', $a_date);

        return $date;
    }



    /**
     * Get miseEnPaiementIntervenantStructure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMiseEnPaiementIntervenantStructure()
    {
        return $this->miseEnPaiementIntervenantStructure;
    }



    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelleLong();
    }

}
