<?php

namespace Application\Entity\Db;

/**
 * Interface des entités possédant une gestion d'historique.
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface ServiceAPayerInterface
{
    /**
     * Get Id
     * 
     * @return integer
     */
    public function getId();

    /**
     * Get heuresComplFi
     *
     * @return float 
     */
    public function getHeuresComplFi();

    /**
     * Get heuresComplFc
     *
     * @return float
     */
    public function getHeuresComplFc();

    /**
     * Get heuresComplFcMajorees
     *
     * @return float
     */
    public function getHeuresComplFcMajorees();

    /**
     * Get heuresComplFa
     *
     * @return float
     */
    public function getHeuresComplFa();

    /**
     * Get heuresComplReferentiel
     *
     * @return float
     */
    public function getHeuresComplReferentiel();

    /**
     * Add miseEnPaiement
     *
     * @param \Application\Entity\Db\MiseEnPaiement $miseEnPaiement
     * @return FormuleResultatService
     */
    public function addMiseEnPaiement(\Application\Entity\Db\MiseEnPaiement $miseEnPaiement);

    /**
     * Remove miseEnPaiement
     *
     * @param \Application\Entity\Db\MiseEnPaiement $miseEnPaiement
     */
    public function removeMiseEnPaiement(\Application\Entity\Db\MiseEnPaiement $miseEnPaiement);

    /**
     * Get miseEnPaiement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMiseEnPaiement();
}