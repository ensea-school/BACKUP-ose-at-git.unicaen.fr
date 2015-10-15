<?php

namespace Application\Entity\Db;

/**
 * Interface spécifiant les accesseurs utiles pour obtenir des informations
 * affichables concernant un intervenant, qu'il vienne d'OSE ou de la source 
 * de données externe (ex: Harpege).
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see \Import\Model\Entity\Intervenant\Intervenant
 * @see Intervenant
 */
interface IntervenantInterface
{
    /**
     * Retourne la représentation littérale de cet objet.
     * 
     * @return string
     */
    public function __toString();

    /**
     * Retourne le nom usuel.
     * 
     * @return string
     */
    public function getNomUsuel();

    /**
     * Retourne le nom patronymique.
     * 
     * @return string
     */
    public function getNomPatronymique();

    /**
     * Retourne le prenom.
     * 
     * @return string
     */
    public function getPrenom();

    /**
     * Get estUneFemme
     *
     * @return bool 
     */
    public function estUneFemme();
    
    /**
     * Get civilite
     *
     * @return string 
     */
    public function getCiviliteToString();
    
    /**
     * Get dateNaissance
     *
     * @return string
     */
    public function getDateNaissanceToString();

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail();

    /**
     * Get affectations
     *
     * @return string 
     */
    public function getAffectationsToString();

    /**
     * Get source id
     *
     * @return integer 
     * @see \Application\Entity\Db\Source
     */
    public function getSourceToString();

    /**
     * Get sourceCode
     *
     * @return string 
     */
    public function getSourceCode();

}