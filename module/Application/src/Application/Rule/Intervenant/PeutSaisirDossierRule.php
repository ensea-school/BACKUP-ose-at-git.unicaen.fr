<?php

namespace Application\Rule\Intervenant;

use Application\Rule\AbstractRule;
use Application\Traits\IntervenantAwareTrait;
use Application\Service\Intervenant as IntervenantService;

/**
 * Règle métier déterminant si un intervenant peut saisir des données personnelles.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirDossierRule extends AbstractRule
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
        
        $qb = $this->getServiceIntervenant()->getRepo()->createQueryBuilder("i")
                ->select("i.id")
                ->join("i.statut", "s")
                ->andWhere("s.peutSaisirDossier = 1");
        
        /**
         * Application de la règle à un intervenant précis
         */
        if ($this->getIntervenant()) {
            $qb->andWhere("i = :intervenant")->setParameter('intervenant', $this->getIntervenant());
            
            $result = $qb->getQuery()->getScalarResult();
            
            if (!$result) {
                $statut = $this->getIntervenant()->getStatut();
                $this->setMessage(sprintf("Le statut &laquo; %s &raquo; n'autorise pas la saisie de données personnelles.", $statut));
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