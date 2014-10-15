<?php

namespace Application\Rule\Intervenant;

use Application\Traits\AnneeAwareTrait;
use Common\Exception\LogicException;

/**
 * Règle métier déterminant si un intervenant a fait l'objet d'une saisie d'enseignements.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PossedeServicesRule extends AbstractIntervenantRule
{
    use AnneeAwareTrait;
    
    const MESSAGE_SERVICE = 'messageService';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = array(
        self::MESSAGE_SERVICE => "Les enseignements de %value% n'ont pas été saisis.",
    );
    
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
        
        $this->message(null);
        
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
                $this->message(self::MESSAGE_SERVICE, $this->getIntervenant());
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
}