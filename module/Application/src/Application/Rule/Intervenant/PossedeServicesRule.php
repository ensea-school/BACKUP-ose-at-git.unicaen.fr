<?php

namespace Application\Rule\Intervenant;

use Application\Traits\AnneeAwareTrait;
use Application\Traits\StructureAwareTrait;
use Common\Exception\LogicException;

/**
 * Règle métier déterminant si un intervenant a fait l'objet d'une saisie d'enseignements.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PossedeServicesRule extends AbstractIntervenantRule
{
    use AnneeAwareTrait;
    use StructureAwareTrait;
    
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
        
        $qb = $this->getQueryBuilder();
        
        /**
         * Application de la règle à un intervenant précis
         */
        if ($this->getIntervenant()) {
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
    
    /**
     * 
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        $qb = $this->getServiceIntervenant()->getRepo()->createQueryBuilder("i")
                ->select("i.id")
                ->join("i.service", "s")
                ->join("s.elementPedagogique", "ep") // permet d'écarter les EP historisés
                ->join("ep.etape", "e")              // permet d'écarter les Etapes historisées
                ->andWhere("s.annee = " . $this->getAnnee()->getId());
        
        if ($this->getIntervenant()) {
            $qb->andWhere("i = " . $this->getIntervenant()->getId());
        }
        
        if ($this->getStructure()) {
            $qb
                    ->join("s.structureEns", "strEns")
                    ->andWhere("strEns = " . $this->getStructure()->getId());
        }
        
        return $qb;
    }
}