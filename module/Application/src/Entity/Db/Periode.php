<?php

namespace Application\Entity\Db;

use Paiement\Entity\Db\MiseEnPaiementIntervenantStructure;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * Periode
 */
class Periode implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;
    const SEMESTRE_1 = 'S1';
    const SEMESTRE_2 = 'S2';
    const PAIEMENT_TARDIF = 'PTD';

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
     * @var integer
     */
    protected $ecartMois;

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
     * Retourne le libellé assorti d'une année à afficher
     *
     * @param Annee $annee
     *
     * @return string
     */
    public function getLibelleAnnuel(Annee $annee)
    {
        if ($this->getCode() === self::PAIEMENT_TARDIF){
            return $this->getLibelleLong();
        }else{
            $datePaiement = $this->getDatePaiement($annee);
            return $this->getLibelleLong().' '.$datePaiement->format('Y');
        }
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
     * Set ecartMois
     *
     * @param integer $ecartMois
     *
     * @return Periode
     */
    public function setEcartMois($ecartMois)
    {
        $this->ecartMois = $ecartMois;

        return $this;
    }



    /**
     * Get ecartMois
     *
     * @return boolean
     */
    public function getEcartMois()
    {
        return $this->ecartMois;
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

        $dm = ((int)$annee->getDateDebut()->format('m') + $this->getEcartMois());

        $da = (int)$annee->getDateDebut()->format('Y') + floor($dm / 12);
        $dm = $dm % 12;

        $a_date = date("Y-m-t", mktime(0, 0, 0, $dm, 1, $da));
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
