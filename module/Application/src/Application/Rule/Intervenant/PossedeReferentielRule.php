<?php

namespace Application\Rule\Intervenant;

use Application\Traits\AnneeAwareTrait;
use Application\Entity\Db\IntervenantPermanent;
use Common\Exception\LogicException;

/**
 * Règle métier déterminant :
 * - si un intervenant précis a fait l'objet d'une saisie de référentiel ;
 * - les intervenants ayant fait l'objet d'une saisie de référentiel.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PossedeReferentielRule extends AbstractIntervenantRule
{
    use AnneeAwareTrait;
    
    const MESSAGE_REFERENTIEL = 'messageReferentiel';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = array(
        self::MESSAGE_REFERENTIEL => "Le référentiel de %value% n'a pas été saisi.",
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
                $this->message(self::MESSAGE_REFERENTIEL, $this->getIntervenant());
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
            return $this->getIntervenant() instanceof IntervenantPermanent;
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
        $qb = $em->getRepository('Application\Entity\Db\IntervenantPermanent')->createQueryBuilder("i")
                ->select("i.id")
                ->join("i.serviceReferentiel", "sr")
                ->andWhere("sr.annee = " . $this->getAnnee()->getId());
        
        if ($this->getIntervenant()) {
            if (!$this->getIntervenant() instanceof IntervenantPermanent) {
                throw new LogicException("L'intervenant spécifié doit être un IntervenantPermanent.");
            }
            
            $qb->andWhere("i = " . $this->getIntervenant()->getId());
        }
        
        return $qb;
    }
}