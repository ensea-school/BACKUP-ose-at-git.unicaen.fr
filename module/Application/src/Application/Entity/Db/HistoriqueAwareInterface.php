<?php

namespace Application\Entity\Db;
use UnicaenAuth\Entity\Db\AbstractUser;

/**
 * Interface des entités possédant une gestion d'historique.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
interface HistoriqueAwareInterface
{
    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return self
     */
    public function setHistoCreation($histoCreation);

    /**
     * Get histoCreation
     *
     * @return \DateTime 
     */
    public function getHistoCreation();

    /**
     * Set histoDestruction
     *
     * @param \DateTime $histoDestruction
     * @return self
     */
    public function setHistoDestruction($histoDestruction);

    /**
     * Get histoDestruction
     *
     * @return \DateTime 
     */
    public function getHistoDestruction();

    /**
     * Set histoModification
     *
     * @param \DateTime $histoModification
     * @return self
     */
    public function setHistoModification($histoModification);

    /**
     * Get histoModification
     *
     * @return \DateTime 
     */
    public function getHistoModification();

    /**
     * Set histoModificateur
     *
     * @param AbstractUser $histoModificateur
     * @return self
     */
    public function setHistoModificateur(AbstractUser $histoModificateur = null);

    /**
     * Get histoModificateur
     *
     * @return AbstractUser
     */
    public function getHistoModificateur();

    /**
     * Set histoDestructeur
     *
     * @param AbstractUser $histoDestructeur
     * @return self
     */
    public function setHistoDestructeur(AbstractUser $histoDestructeur = null);

    /**
     * Get histoDestructeur
     *
     * @return AbstractUser
     */
    public function getHistoDestructeur();

    /**
     * Set histoCreateur
     *
     * @param AbstractUser $histoCreateur
     * @return self
     */
    public function setHistoCreateur(AbstractUser $histoCreateur = null);

    /**
     * Get histoCreateur
     *
     * @return AbstractUser
     */
    public function getHistoCreateur();
}