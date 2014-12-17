<?php

namespace Application\Entity\Db;

use DateTime;

/**
 * NotificationIndicateur
 */
class NotificationIndicateur
{
    const FREQUENCE_JOUR    = "Jour";
    const FREQUENCE_SEMAINE = "Semaine";
    
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
    protected $dateNotification;

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
     * Set frequence
     *
     * @param DateTime $date
     * @return NotificationIndicateur
     */
    public function setDateNotification(DateTime $date)
    {
        $this->frequence = $date;

        return $this;
    }

    /**
     * Get frequence
     *
     * @return DateTime 
     */
    public function getDateNotification()
    {
        return $this->frequence;
    }
}