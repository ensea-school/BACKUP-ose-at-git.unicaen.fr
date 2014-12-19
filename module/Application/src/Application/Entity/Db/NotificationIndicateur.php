<?php

namespace Application\Entity\Db;

use DateTime;

/**
 * NotificationIndicateur
 */
class NotificationIndicateur
{
    const FREQUENCE_HEURE   = "heure";
    const FREQUENCE_JOUR    = "jour";
    const FREQUENCE_SEMAINE = "semaine";
    
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
     * @param string $frequence
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
     * @return string 
     */
    public function getFrequence()
    {
        return $this->frequence;
    }
    
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