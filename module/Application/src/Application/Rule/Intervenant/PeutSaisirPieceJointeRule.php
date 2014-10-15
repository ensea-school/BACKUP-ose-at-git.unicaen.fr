<?php

namespace Application\Rule\Intervenant;

/**
 * Règle métier déterminant si un intervenant peut joindre des pièces justificatives.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirPieceJointeRule extends AbstractIntervenantRule
{    
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
        
        $statut = $this->getIntervenant()->getStatut();
        
        if (!$statut->peutSaisirPieceJointe()) {
            $this->setMessage(sprintf("Le statut &laquo; %s &raquo; ne nécessite pas la fourniture de pièces justificatives.", $statut));
            return false;
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return true;
    }
}