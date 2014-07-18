<?php

namespace Application\Entity\Db;

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
     * @return IntervenantPermanent
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
     * @return IntervenantPermanent
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
     * @return IntervenantPermanent
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
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return IntervenantPermanent
     */
    public function setHistoModificateur(\Application\Entity\Db\Utilisateur $histoModificateur = null);

    /**
     * Get histoModificateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoModificateur();

    /**
     * Set histoDestructeur
     *
     * @param \Application\Entity\Db\Utilisateur $histoDestructeur
     * @return IntervenantPermanent
     */
    public function setHistoDestructeur(\Application\Entity\Db\Utilisateur $histoDestructeur = null);

    /**
     * Get histoDestructeur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoDestructeur();

    /**
     * Set histoCreateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoCreateur
     * @return IntervenantPermanent
     */
    public function setHistoCreateur(\Application\Entity\Db\Utilisateur $histoCreateur = null);

    /**
     * Get histoCreateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoCreateur();
}