<?php

namespace Application\Rule\Intervenant;

use Application\Traits\StructureAwareTrait;
use Application\Entity\Db\Structure;
use Common\Exception\LogicException;

/**
 * Règle métier déterminant si du référentiel peut être saisi.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirReferentielRule extends AbstractIntervenantRule
{
    use StructureAwareTrait;
 
    const MESSAGE_STATUT     = 'messageStatut';
    const MESSAGE_STRUCTURE  = 'messageStructure';
    const MESSAGE_IMPOSSIBLE = 'messageStatutOuStructure';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = array(
        self::MESSAGE_STATUT     => "Le statut &laquo; %value% &raquo; n'autorise pas la saisie de référentiel.",
        self::MESSAGE_STRUCTURE  => "La saisie de référentiel au sein de la structure &laquo; %value% &raquo; n'est pas possible.",
        self::MESSAGE_IMPOSSIBLE => "La saisie de référentiel n'est pas possible.",
    );

    /**
     * 
     * @return array
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
                $this->message(self::MESSAGE_IMPOSSIBLE, $statut);
            }
        
            return $this->normalizeResult($result);
        }
        
        /**
         * Recherche des intervenants répondant à la règle
         */
        
        $result = $qb->getQuery()->getScalarResult();
        
        return $this->normalizeResult($result);
        
//        $estPermanent = new EstPermanentRule($this->getIntervenant());
//        if (!$estPermanent->execute()) {
//            $this->setMessage($estPermanent->getMessage());
//            return false;
//        }
//        
//        if ($this->getStructure()) {
//            $estAffecte = new EstAffecteRule($this->getIntervenant(), $this->getStructure());
//            if (!$estAffecte->execute()) {
//                $this->setMessage($estAffecte->getMessage());
//                return false;
//            }
//        }
//        
//        return true;
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
                ->join("i.statut", "s")
                ->andWhere("s.peutSaisirReferentiel = 1");
         
        if ($this->getStructure()) {
            $qb
                    ->join("i.structure", "saff")
                    ->join("saff.structureNiv2", "saff2")
                    ->andWhere("saff2 = :strNiv2")->setParameter('strNiv2', $this->getStructure());
        }
        
        if ($this->getIntervenant()) {
            $qb->andWhere("i = :intervenant")->setParameter('intervenant', $this->getIntervenant());
        }
        
        return $qb;
    }
    
    /**
     * Spécifie la structure concernée.
     * 
     * @param Structure $structure Structure concernée
     */
    public function setStructure(Structure $structure = null)
    {
        if ($structure && 2 !== $structure->getNiveau()) {
            throw new LogicException("La structure spécifiée doit être de niveau 2.");
        }
        
        $this->structure = $structure;
        
        return $this;
    }
}