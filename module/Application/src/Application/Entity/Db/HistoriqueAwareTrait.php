<?php

namespace Application\Entity\Db;

use Common\Constants;
use UnicaenAuth\Entity\Db\AbstractUser;

/**
 * Code commun aux entités possédant une gestion d'historique.
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait HistoriqueAwareTrait
{
    /**
     * @var \DateTime
     */
    protected $histoCreation;

    /**
     * @var \DateTime
     */
    protected $histoModification;

    /**
     * @var \DateTime
     */
    protected $histoDestruction;

    /**
     * @var AbstractUser
     */
    protected $histoCreateur;

    /**
     * @var AbstractUser
     */
    protected $histoModificateur;

    /**
     * @var AbstractUser
     */
    protected $histoDestructeur;



    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     *
     * @return self
     */
    public function setHistoCreation($histoCreation)
    {
        $this->histoCreation = $histoCreation;

        return $this;
    }



    /**
     * Get histoCreation
     *
     * @return \DateTime
     */
    public function getHistoCreation()
    {
        return $this->histoCreation;
    }



    /**
     * Set histoDestruction
     *
     * @param \DateTime $histoDestruction
     *
     * @return self
     */
    public function setHistoDestruction($histoDestruction)
    {
        $this->histoDestruction = $histoDestruction;

        return $this;
    }



    /**
     * Get histoDestruction
     *
     * @return \DateTime
     */
    public function getHistoDestruction()
    {
        return $this->histoDestruction;
    }



    /**
     * Set histoModification
     *
     * @param \DateTime $histoModification
     *
     * @return self
     */
    public function setHistoModification($histoModification)
    {
        $this->histoModification = $histoModification;

        return $this;
    }



    /**
     * Get histoModification
     *
     * @return \DateTime
     */
    public function getHistoModification()
    {
        return $this->histoModification;
    }



    /**
     * Retourne la date et l'auteur de la dernière modification au format "Le dd/mm/yyyy à hh:mm par Tartanpion".
     *
     * @return string
     */
    public function getHistoModificationEtModificateurToString()
    {
        return sprintf("Le %s par %s", $this->getHistoModification()->format(Constants::DATETIME_FORMAT), $this->getHistoModificateur());
    }



    /**
     * Set histoModificateur
     *
     * @param AbstractUser $histoModificateur
     *
     * @return self
     */
    public function setHistoModificateur(AbstractUser $histoModificateur = null)
    {
        $this->histoModificateur = $histoModificateur;

        return $this;
    }



    /**
     * Get histoModificateur
     *
     * @return AbstractUser
     */
    public function getHistoModificateur()
    {
        return $this->histoModificateur;
    }



    /**
     * Set histoDestructeur
     *
     * @param AbstractUser $histoDestructeur
     *
     * @return self
     */
    public function setHistoDestructeur(AbstractUser $histoDestructeur = null)
    {
        $this->histoDestructeur = $histoDestructeur;

        return $this;
    }



    /**
     * Get histoDestructeur
     *
     * @return AbstractUser
     */
    public function getHistoDestructeur()
    {
        return $this->histoDestructeur;
    }



    /**
     * Set histoCreateur
     *
     * @param AbstractUser $histoCreateur
     *
     * @return self
     */
    public function setHistoCreateur(AbstractUser $histoCreateur = null)
    {
        $this->histoCreateur = $histoCreateur;

        return $this;
    }



    /**
     * Get histoCreateur
     *
     * @return AbstractUser
     */
    public function getHistoCreateur()
    {
        return $this->histoCreateur;
    }



    /**
     * Détermine si l'entité est historisée ou non
     *
     * @param \DateTime|null $dateObs
     *
     * @return bool
     */
    public function estNonHistorise(\DateTime $dateObs = null)
    {
        if (empty($dateObs)) $dateObs = new \DateTime();


        $dObs = $dateObs->format('Y-m-d');
        $dDeb = $this->getHistoCreation() ? $this->getHistoCreation()->format('Y-m-d') : null;
        $dFin = $this->getHistoDestruction() ? $this->getHistoDestruction()->format('Y-m-d') : null;

        if ($dDeb && !($dDeb <= $dObs)) return false;
        if ($dFin && !($dObs < $dFin)) return false;

        return true;
    }
}