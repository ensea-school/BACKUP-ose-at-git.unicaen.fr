<?php

namespace Application\Rule\Intervenant;

/**
 * Règle métier déterminant si un intervenant peut joindre des pièces justificatives.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirPieceJointeRule extends AbstractIntervenantRule
{    
    const MESSAGE_STATUT = 'messageStatut';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = array(
        self::MESSAGE_STATUT => "Le statut &laquo; %value% &raquo; ne nécessite pas la fourniture de pièce justificative.",
    );
    
    /**
     * Exécute la règle métier.
     * 
     * @return array [ integer => [ 'id' => {id} ] ]
     */
    public function execute()
    {
        $this->message(null);
        
        $qb = $this->getQueryBuilder();
        
        /**
         * Application de la règle à un intervenant précis
         */
        if ($this->getIntervenant()) {
            $result = $qb->getQuery()->getScalarResult();
            
            if (!$result) {
                $statut = $this->getIntervenant()->getStatut();
                $this->message(self::MESSAGE_STATUT, $statut);
            }
                
            return $this->normalizeResult($result);
        }
        
        /**
         * Recherche des intervenants répondant à la règle
         */
        
        $result = $qb->getQuery()->getScalarResult();

        return $this->normalizeResult($result);
    }
    
    public function isRelevant()
    {
        return true;
    }
    
    /**
     * 
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        $em = $this->getServiceIntervenant()->getEntityManager();
        $qb = $em->getRepository("Application\Entity\Db\IntervenantExterieur")->createQueryBuilder("i")
                ->select("i.id")
                ->join("i.dossier", "d")
                ->join("d.statut", "si")
                ->join("si.typePieceJointeStatut", "tpjs");
        
        if ($this->getIntervenant()) {
            $qb->andWhere("i = :intervenant")->setParameter('intervenant', $this->getIntervenant());
        }
        
        return $qb;
    }
}