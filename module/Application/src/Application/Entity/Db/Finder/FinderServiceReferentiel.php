<?php

namespace Application\Entity\Db\Finder;

use Application\Acl\DbRole;
use Application\Acl\IntervenantRole;

/**
 * Requêteur contextualisé de services référentiels.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see \Application\Service\ContextProvider
 */
class FinderServiceReferentiel extends AbstractFinder
{
    /**
     * 
     * @return self
     */
    protected function createQuery()
    {
        $context = $this->getContextProvider()->getGlobalContext();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();
        
        $this
                ->select('sr')
                ->from("Application\Entity\Db\ServiceReferentiel", 'sr')
                ->leftJoin("sr.structure", 's')
                ->join("sr.intervenant", 'i')
                ->join("sr.fonction", 'f')
                ->orderBy("i.nomUsuel, s.libelleCourt");
        
        if ($role instanceof IntervenantRole) {
            $this
                    ->andWhere("sr.intervenant = :intervenant")
                    ->setParameter('intervenant', $context->getIntervenant());
        }
        elseif ($role instanceof DbRole) {
            $e = $this->expr()->orX(
                    "sr.structure     = :structureResp", 
                    "s2.structureNiv2 = :structureResp"
            );
            $this
                    ->join("i.structure", 's2')
                    ->andWhere($e)
                    ->setParameter('structureResp', $role->getStructure());
        }
        
        if (($annee = $context->getAnnee())) {
            $this
                    ->andWhere("sr.annee = :annee")
                    ->setParameter('annee', $annee);
        }
        
        $this->applyLocalContext();
        
        return $this;
    }
    
    /**
     * Applique le contexte local (filtres).
     * 
     * @return self
     */
    public function applyLocalContext()
    {
        $filter = $this->getContextProvider()->getLocalContext();
//        $filter->debug();
        
        if (($intervenant = $filter->getIntervenant())) {
            $this
                    ->andWhere("sr.intervenant = :intervenant")
                    ->setParameter('intervenant', $intervenant);
        }
        if (($structureEns = $filter->getStructure())) {
            $this
                    ->andWhere("sr.structure = :structure")
                    ->setParameter('structure', $structureEns);
        }
        if (($statutInterv = $filter->getStatutInterv()) && $statutInterv !== "Application\Entity\Db\IntervenantPermanent") {
            $this->andWhere("0 = 1");
        }
        
        return $this;
    }
}