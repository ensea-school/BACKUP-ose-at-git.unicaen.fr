<?php

namespace Indicateur\Entity\Db;

use Application\Constants;
use DateTime;

/**
 * NotificationIndicateur
 */
class NotificationIndicateur
{
    const PERIODE_HEURE_3 = 10800;  // 60*60*3    = 3h
    const PERIODE_HEURE_6 = 21600;  // 60*60*6    = 6h
    const PERIODE_JOUR    = 86400;  // 60*60*24   = 1j
    const PERIODE_SEMAINE = 604800; // 60*60*24*7 = 7j

    /**
     * Liste des féquences possibles.
     *
     * Attention, les fréquences "4 par jour" (période = 3h) et "2 par jour" (période = 6h)
     * n'ont de sens qu'en accord avec la configuration du CRON chargé d'exécuter le script de notification.
     * Par exemple, un CRON configuré pour se réveiller chaque jour de 7h à 18h toutes les heures pourra
     * honorer ces fréquences :
     * - "4 par jour" (période = 3h) : notification possible à 7h, 10h, 13h puis 16h.
     * - "2 par jour" (période = 6h) : notification possible à 7h puis 13h.
     *
     * @var array
     */
    static public $frequences = [
        self::PERIODE_HEURE_3 => "4 par jour",
        self::PERIODE_HEURE_6 => "2 par jour",
        self::PERIODE_JOUR    => "1 par jour",
        self::PERIODE_SEMAINE => "1 par semaine",
    ];

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Affectation
     */
    protected $affectation;

    /**
     * @var Indicateur
     */
    protected $indicateur;

    /**
     * @var string
     */
    protected $frequence;

    /**
     * @var boolean
     */
    protected $inHome;

    /**
     * @var DateTime
     */
    protected $dateAbonnement;

    /**
     * @var DateTime
     */
    protected $dateDernNotif;



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
     * Set indicateur
     *
     * @param Indicateur $indicateur
     *
     * @return NotificationIndicateur
     */
    public function setIndicateur(Indicateur $indicateur = null)
    {
        $this->indicateur = $indicateur;

        return $this;
    }



    /**
     * Get indicateur
     *
     * @return Indicateur
     */
    public function getIndicateur()
    {
        return $this->indicateur;
    }



    /**
     * @return Affectation
     */
    public function getAffectation()
    {
        return $this->affectation;
    }



    /**
     * @param Affectation $affectation
     *
     * @return NotificationIndicateur
     */
    public function setAffectation($affectation)
    {
        $this->affectation = $affectation;

        return $this;
    }



    /**
     * Set frequence
     *
     * @param integer $frequence
     *
     * @return NotificationIndicateur
     */
    public function setFrequence($frequence)
    {
        $this->frequence = $frequence;

        return $this;
    }



    /**
     * Get frequence
     *
     * @return integer
     */
    public function getFrequence()
    {
        return $this->frequence;
    }



    /**
     * @return boolean
     */
    public function getInHome()
    {
        return $this->inHome;
    }



    /**
     * @param boolean $inHome
     *
     * @return NotificationIndicateur
     */
    public function setInHome($inHome)
    {
        $this->inHome = $inHome;

        return $this;
    }



    /**
     * Get frequence
     *
     * @return string
     */
    public function getFrequenceToString()
    {
        return static::$frequences[$this->getFrequence()];
    }



    /**
     * Set dateDernNotif
     *
     * @param DateTime $date
     *
     * @return NotificationIndicateur
     */
    public function setDateDernNotif(DateTime $date)
    {
        $this->dateDernNotif = $date;

        return $this;
    }



    /**
     * Get dateDernNotif
     *
     * @return DateTime
     */
    public function getDateDernNotif()
    {
        return $this->dateDernNotif;
    }



    /**
     * Get dateDernNotif
     *
     * @return DateTime
     */
    public function getDateDernNotifToString()
    {
        return $this->dateDernNotif ? $this->dateDernNotif->format(Constants::DATETIME_FORMAT) : null;
    }



    /**
     * Get dateDernNotif
     *
     * @return DateTime
     */
    public function getDateProchaineNotifToString()
    {
        if (!$this->dateDernNotif) {
            return null;
        }

        $next = (new \DateTime())->setTimestamp($this->dateDernNotif->getTimestamp() + $this->getFrequence());

        return $next->format(Constants::DATETIME_FORMAT);
    }



    /**
     * Set dateAbonnement
     *
     * @param DateTime $date
     *
     * @return NotificationIndicateur
     */
    public function setDateAbonnement(DateTime $date)
    {
        $this->dateAbonnement = $date;

        return $this;
    }



    /**
     * Get dateAbonnement
     *
     * @return DateTime
     */
    public function getDateAbonnement()
    {
        return $this->dateAbonnement;
    }



    /**
     * Get dateAbonnement
     *
     * @return DateTime
     */
    public function getDateAbonnementToString()
    {
        return $this->dateAbonnement->format(Constants::DATETIME_FORMAT);
    }



    /**
     *
     * @return string
     */
    public function getExtraInfos()
    {
        $infos = "Abonnement : " . $this->getDateAbonnement()->format(Constants::DATETIME_FORMAT);

        $infos .= "<br />Structure : " . ($this->getAffectation()->getStructure() ?: "aucune");

        if (($dernNotif = $this->getDateDernNotif())) {
            $infos .= "<br />Dernière notification : " . $dernNotif->format(Constants::DATETIME_FORMAT);
        }

        return $infos;
    }
}