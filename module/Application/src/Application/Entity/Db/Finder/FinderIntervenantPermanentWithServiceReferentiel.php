<?php

namespace Application\Entity\Db\Finder;

use Application\Acl\DbRole;
use Application\Acl\IntervenantRole;
use Application\Entity\Db\IntervenantPermanent;

/**
 * Requêteur contextualisé d'intervenants permanents.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see \Application\Service\ContextProvider
 */
class FinderIntervenantPermanentWithServiceReferentiel extends AbstractFinder
{
    /**
     * 
     * @param int|IntervenantPermanent $intervenant
     * @return self
     */
    public function setIntervenant($intervenant)
    {
        if ($intervenant instanceof IntervenantPermanent) {
            $intervenant = $intervenant->getId();
        }
        
        $this
                ->andWhere('i.id = :id')
                ->setParameter('id', $intervenant);
        
        return $this;
    }
    
    /**
     * 
     * @return self
     */
    protected function createQuery()
    {
        $this
                ->select('i')
                ->from('Application\Entity\Db\IntervenantPermanent', 'i')
                ->leftJoin('i.serviceReferentiel', 'sr')
                ->leftJoin('sr.fonction', 'fr')
                ->leftJoin('sr.structure', 's')
                ->orderBy('s.libelleCourt');
        
        return $this;
    }
    
    /**
     * 
     * @return self
     */
    protected function applyGlobalContext()
    {
        if (!$this->getContextProvider()) {
            return $this;
        }
        
        $context = $this->getContextProvider()->getGlobalContext();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof IntervenantRole) {
//            $this
//                    ->andWhere("sr.intervenant = :intervenant")
//                    ->setParameter('intervenant', $context->getIntervenant());
        }
        elseif ($role instanceof DbRole) {
            $this
                    ->andWhere("sr.structure = :structureResp")
                    ->setParameter('structureResp', $role->getStructure());
        }
        
        if (($annee = $context->getAnnee())) {
            $this
                    ->andWhere("sr.annee = :annee or sr.annee is null") // because left join
                    ->setParameter('annee', $annee);
        }
        
        return $this;
    }   
}