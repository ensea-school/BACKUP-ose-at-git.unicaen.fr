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
        $context = $this->getContextProvider()->getGlobalContext();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();
        
        $this
                ->select('i')
                ->from('Application\Entity\Db\IntervenantPermanent', 'i');
        
        $condition = $this->expr()->eq('sr.intervenant', 'i');
                
        if (($annee = $context->getAnnee())) {
            $condition = $this->expr()->andX($condition, $this->expr()->eq('sr.annee', ':annee'));
            $this->setParameter('annee', $annee);
        }
        
        if ($role instanceof IntervenantRole) {
//            $this
//                    ->andWhere("sr.intervenant = :intervenant")
//                    ->setParameter('intervenant', $context->getIntervenant());
        }
        elseif ($role instanceof DbRole) {
            $or = $this->expr()->orX(
                    "sr.structure       = :structureResp", 
                    "stmp.structureNiv2 = :structureResp"
            );
            $condition = $this->expr()->andX($condition, $or);
            $this
                    ->leftJoin('i.serviceReferentiel', 'srtmp')
                    ->leftJoin('srtmp.structure',      'stmp')
                    ->setParameter('structureResp', $role->getStructure());
        }
        
        $this
                ->leftJoin('i.serviceReferentiel', 'sr', \Doctrine\ORM\Query\Expr\Join::WITH, $condition)
                ->leftJoin('sr.fonction', 'fr')
                ->leftJoin('sr.structure', 's')
                ->orderBy('s.libelleCourt');

        return $this;
    }
}