<?php

namespace Application\Rule\Intervenant;

use Application\Rule\AbstractRule;
use Application\Traits\IntervenantAwareTrait;
use Application\Entity\Db\IntervenantExterieur;
use Application\Service\Intervenant as IntervenantService;
use Common\Exception\LogicException;

/**
 * Règle métier déterminant si un intervenant a saisi des données personnelles.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PossedeDossierRule extends AbstractRule
{
    use IntervenantAwareTrait;
    
    /**
     * Exécute la règle métier.
     * 
     * @return array [ integer => [ 'id' => {id} ] ]
     */
    public function execute()
    {
        $this->setMessage(null);
        
        $em = $this->getServiceIntervenant()->getEntityManager();
        $qb = $em->getRepository('Application\Entity\Db\IntervenantExterieur')->createQueryBuilder("i")
                ->select("i.id")
                ->join("i.dossier", "d");
        
        /**
         * Application de la règle à un intervenant précis
         */
        if ($this->getIntervenant()) {
            if (!$this->getIntervenant() instanceof IntervenantExterieur) {
                throw new LogicException("L'intervenant spécifié doit être un IntervenantExterieur.");
            }
            
            $qb->andWhere("i = :intervenant")->setParameter('intervenant', $this->getIntervenant());
            
            $result = $qb->getQuery()->getScalarResult();
            
            if (!$result) {
                $this->setMessage("Les données personnelles de l'intervenant doivent avoir été saisies au préalable.");
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
            return $this->getIntervenant()->getStatut()->getPeutSaisirDossier();
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
