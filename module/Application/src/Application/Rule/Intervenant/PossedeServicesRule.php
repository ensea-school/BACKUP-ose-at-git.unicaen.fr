<?php

namespace Application\Rule\Intervenant;

use Application\Rule\AbstractRule;
use Application\Traits\IntervenantAwareTrait;
use Application\Traits\AnneeAwareTrait;
use Application\Service\Intervenant as IntervenantService;
use Common\Exception\LogicException;

/**
 * Règle métier déterminant si un intervenant a fait l'objet d'une saisie d'enseignements.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PossedeServicesRule extends AbstractRule
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
        if (!$this->getAnnee()) {
            throw new LogicException("Une année est requise.");
        }
        
        $this->setMessage(null);
        
        $qb = $this->getServiceIntervenant()->getRepo()->createQueryBuilder("i")
                ->select("i.id")
                ->join("i.service", "s")
                ->andWhere("s.annee = :annee")->setParameter('annee', $this->getAnnee());
        
        /**
         * Application de la règle à un intervenant précis
         */
        if ($this->getIntervenant()) {
            $qb->andWhere("i = :intervenant")->setParameter('intervenant', $this->getIntervenant());
            
            $result = $qb->getQuery()->getScalarResult();
            
            if (!$result) {
                $this->setMessage(sprintf("Les enseignements de %s n'ont pas été saisis.", $this->getIntervenant()));
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