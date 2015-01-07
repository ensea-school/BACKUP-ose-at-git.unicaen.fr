<?php

namespace Application\Entity\Db;

use DateTime;

/**
 * NotificationIndicateur
 */
class NotificationIndicateur
{
    const FREQUENCE_HEURE   = 3600;   // 60*60
    const FREQUENCE_JOUR    = 86400;  // 60*60*24
    const FREQUENCE_SEMAINE = 604800; // 60*60*24*7;
    
    static public $frequences = [
        self::FREQUENCE_HEURE => "Une par heure",
        self::FREQUENCE_JOUR => "Une par jour",
        self::FREQUENCE_SEMAINE => "Une par semaine",
    ];
    
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Structure
     */
    protected $structure;

    /**
     * @var Indicateur
     */
    protected $indicateur;

    /**
     * @var Personnel
     */
    protected $personnel;

    /**
     * @var string
     */
    protected $frequence;

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
     * Set structure
     *
     * @param Structure $structure
     * @return NotificationIndicateur
     */
    public function setStructure(Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * Get structure
     *
     * @return Structure 
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Set personnel
     *
     * @param Personnel $personnel
     * @return NotificationIndicateur
     */
    public function setPersonnel(Personnel $personnel = null)
    {
        $this->personnel = $personnel;

        return $this;
    }

    /**
     * Get personnel
     *
     * @return Personnel 
     */
    public function getPersonnel()
    {
        return $this->personnel;
    }
    
    /**
     * Set frequence
     *
     * @param integer $frequence
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
//
//    /**
//     * Get frequence
//     *
//     * @return string 
//     */
//    public function getFrequenceInSeconds()
//    {
//        switch ($this->getFrequence()) {
//            case self::FREQUENCE_HEURE:
//                return 60*60;
//            case self::FREQUENCE_JOUR:
//                return 60*60*24;
//            case self::FREQUENCE_SEMAINE:
//                return 60*60*24*7;
//            default:
//                throw new \DomainException("Fréquence rencontrée inattendue: '{$this->getFrequence()}'.");
//        }
//    }
    
    /**
     * Set dateDernNotif
     *
     * @param DateTime $date
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
     * Set dateAbonnement
     *
     * @param DateTime $date
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
     * 
     * @return string
     */
    public function getExtraInfos()
    {
        $infos = "Abonnement : " . $this->getDateAbonnement()->format(\Common\Constants::DATETIME_FORMAT);
        
        if (($dernNotif = $this->getDateDernNotif())) {
            $infos .= "<br />Dernière notification : " . $dernNotif->format(\Common\Constants::DATETIME_FORMAT);
        }
        
        return $infos;
    }
}