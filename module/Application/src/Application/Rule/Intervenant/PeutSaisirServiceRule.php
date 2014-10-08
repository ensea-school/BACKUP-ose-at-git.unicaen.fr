<?php

namespace Application\Rule\Intervenant;

use Application\Rule\AbstractRule;
use Application\Traits\IntervenantAwareTrait;
use Application\Service\Intervenant as IntervenantService;

/**
 * Règle métier déterminant si des enseignements peuvent être saisis pour un intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirServiceRule extends AbstractRule
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
                ->andWhere("s.peutSaisirService = 1");
        
        /**
         * Application de la règle à un intervenant précis
         */
        if ($this->getIntervenant()) {
            $qb->andWhere("i = :intervenant")->setParameter('intervenant', $this->getIntervenant());
            
            $result = $qb->getQuery()->getScalarResult();
            
            if (!$result) {
                $statut = $this->getIntervenant()->getStatut();
                $this->setMessage(sprintf("Le statut &laquo; %s &raquo; n'autorise pas la saisie d'enseignement.", $statut));
            }
                
            return $result;
        }
        
        /**
         * Recherche des intervenants répondant à la règle
         */
        
        $result = $qb->getQuery()->getScalarResult();

        return $result;
    }

    /**
     * @todo Pour un intervenant qui n'a pas encore saisi ses données perso, 
     * cette règle n'est pas pertinente car il peut changer de statut à l'issu de la
     * saisie de ses données perso... A voir si c'est nécessaire.
     * 
     * @return boolean
     */
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