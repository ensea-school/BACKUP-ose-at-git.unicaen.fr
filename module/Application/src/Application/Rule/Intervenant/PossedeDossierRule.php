<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\IntervenantExterieur;
use Common\Exception\LogicException;

/**
 * Règle métier déterminant si un intervenant a saisi des données personnelles.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PossedeDossierRule extends AbstractIntervenantRule
{
    const MESSAGE_DOSSIER = 'messageDossier';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = array(
        self::MESSAGE_DOSSIER => "Les données personnelles de l'intervenant n'ont pas été saisies.",
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
                $this->message(self::MESSAGE_DOSSIER);
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
        if ($this->getIntervenant()) {
            return $this->getIntervenant()->getStatut()->getPeutSaisirDossier();
        }
        
        return true;
    }
    
    /**
     * 
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        $em = $this->getServiceIntervenant()->getEntityManager();
        $qb = $em->getRepository('Application\Entity\Db\IntervenantExterieur')->createQueryBuilder("i")
                ->select("i.id")
                ->join("i.dossier", "d");
        
        if ($this->getIntervenant()) {
            if (!$this->getIntervenant() instanceof IntervenantExterieur) {
                throw new LogicException("L'intervenant spécifié doit être un IntervenantExterieur.");
            }
            
            $qb->andWhere("i = " . $this->getIntervenant()->getId());
        }
        
        return $qb;
    }
}
