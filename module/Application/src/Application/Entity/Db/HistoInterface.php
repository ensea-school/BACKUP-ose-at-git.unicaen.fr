<?php

namespace Application\Entity\Db;

/**
 * Interface des entités possédant les attributs de gestion d'historique.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
interface HistoInterface
{
    /**
     * Set histoCreateur
     *
     * @param integer $histoCreateur
     * @return Annee
     */
    public function setHistoCreateur($histoCreateur);

    /**
     * Get histoCreateur
     *
     * @return integer 
     */
    public function getHistoCreateur();

    /**
     * Set histoDebut
     *
     * @param \DateTime $histoDebut
     * @return Annee
     */
    public function setHistoDebut($histoDebut);

    /**
     * Get histoDebut
     *
     * @return \DateTime 
     */
    public function getHistoDebut();

    /**
     * Set histoDestructeur
     *
     * @param integer $histoDestructeur
     * @return Annee
     */
    public function setHistoDestructeur($histoDestructeur);

    /**
     * Get histoDestructeur
     *
     * @return integer 
     */
    public function getHistoDestructeur();

    /**
     * Set histoFin
     *
     * @param \DateTime $histoFin
     * @return Annee
     */
    public function setHistoFin($histoFin);

    /**
     * Get histoFin
     *
     * @return \DateTime 
     */
    public function getHistoFin();

    /**
     * Set histoModificateur
     *
     * @param integer $histoModificateur
     * @return Annee
     */
    public function setHistoModificateur($histoModificateur);

    /**
     * Get histoModificateur
     *
     * @return integer 
     */
    public function getHistoModificateur();

    /**
     * Set histoModification
     *
     * @param \DateTime $histoModification
     * @return Annee
     */
    public function setHistoModification($histoModification);

    /**
     * Get histoModification
     *
     * @return \DateTime 
     */
    public function getHistoModification();
}