<?php

namespace Application\Rule\Intervenant;

use Application\Rule\AbstractRule;
use Application\Traits\IntervenantAwareTrait;
use Application\Traits\AnneeAwareTrait;
use Application\Entity\Db\IntervenantPermanent;
use Application\Service\Intervenant as IntervenantService;
use Common\Exception\LogicException;

/**
 * Règle métier déterminant si un intervenant a fait l'objet d'une saisie d'enseignements.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PossedeReferentielRule extends AbstractRule
{
    use IntervenantAwareTrait;
    use AnneeAwareTrait;
    
    /**
     * Exécute la règle métier.
     * 
     * @return array [ integer => [ 'id' => {id} ] ]
     */
    public function execute()
    {
        $this->setMessage(null);
        
        $em = $this->getServiceIntervenant()->getEntityManager();
        $qb = $em->getRepository('Application\Entity\Db\IntervenantPermanent')->createQueryBuilder("i")
                ->select("i.id")
                ->join("i.serviceReferentiel", "sr")
                ->andWhere("sr.annee = :annee")->setParameter('annee', $this->getAnnee());
        
        /**
         * Application de la règle à un intervenant précis
         */
        if ($this->getIntervenant()) {
            if (!$this->getIntervenant() instanceof IntervenantPermanent) {
                throw new LogicException("L'intervenant spécifié doit être un IntervenantPermanent.");
            }
            
            $qb->andWhere("i = :intervenant")->setParameter('intervenant', $this->getIntervenant());
            
            $result = $qb->getQuery()->getScalarResult();
            
            if (!$result) {
                $this->setMessage(sprintf("Le référentiel de %s n'a pas été saisi.", $this->getIntervenant()));
            }
                
            return $result;
        }
        
        /**
         * Recherche des intervenants répondant à la règle
         */
        
        $result = $qb->getQuery()->getScalarResult();

        return $result;
    }
    
    public function isRelevant()
    {
        if ($this->getIntervenant()) {
            return $this->getIntervenant() instanceof IntervenantPermanent;
        }
        
        return true;
    }
    
    /**
     * @return IntervenantService
     */
    private function getServiceIntervenant()
    {
        return $this->getServiceLocator()->get('ApplicationIntervenant');
    }
}