<?php

namespace Application\Interfaces;

use Application\Entity\Db\Annee;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface AnneeAwareInterface
{
    
    /**
     * Spécifie l'annee concernée.
     * 
     * @param Annee $annee Annee concernée
     * @return self
     */
    public function setAnnee(Annee $annee);
    
    /**
     * Retourne l'annee concernée.
     * 
     * @return Annee
     */
    public function getAnnee();
}