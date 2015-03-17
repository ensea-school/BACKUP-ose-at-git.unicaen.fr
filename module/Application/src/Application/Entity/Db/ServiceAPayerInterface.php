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
     *
     * @param TypeHeures $typeHeures
     * @return float
     * @throws \Common\Exception\RuntimeException
     */
    public function getHeuresCompl( TypeHeures $typeHeures );

    /**
     * Add miseEnPaiement
     *
     * @param MiseEnPaiement $miseEnPaiement
     * @return FormuleResultatService
     */
    public function addMiseEnPaiement(MiseEnPaiement $miseEnPaiement);

    /**
     * Remove miseEnPaiement
     *
     * @param MiseEnPaiement $miseEnPaiement
     */
    public function removeMiseEnPaiement(MiseEnPaiement $miseEnPaiement);

    /**
     * Get miseEnPaiement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMiseEnPaiement();

    /**
     * @return MiseEnPaiementListe
     */
    public function getMiseEnPaiementListe( \DateTime $dateMiseEnPaiement=null, Periode $periodePaiement=null );

    /**
     * Get centreCout
     *
     * @param TypeHeures $typeHeures
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentreCout( TypeHeures $typeHeures=null );

    /**
     *
     * @param TypeHeures $typeHeures
     * @return CentreCout|null
     */
    public function getDefaultCentreCout( TypeHeures $typeHeures );

    /**
     * @return Structure
     */
    public function getStructure();

    /**
     * Get formuleResultat
     *
     * @return \Application\Entity\Db\FormuleResultat
     */
    public function getFormuleResultat();

    /**
     * @return boolean
     */
    public function isPayable();
}