<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\TypeValidation;
use Application\Service\TypeValidation as TypeValidationService;
use Common\Exception\LogicException;
use Doctrine\ORM\QueryBuilder;

/**
 * Règle métier déterminant si les données personnelles d'un intervenant sont validées.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DossierValideRule extends AbstractIntervenantRule
{
    const MESSAGE_DOSSIER = 'messageDossier';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = array(
        self::MESSAGE_DOSSIER => "Les données personnelles de l'intervenant n'ont pas encore été validées.",
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
            return $this->getIntervenant() instanceof IntervenantExterieur && null !== $this->getIntervenant()->getDossier();
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
        $qb = $em->getRepository('Application\Entity\Db\Intervenant')->createQueryBuilder("i")
                ->select("i.id")
                ->join("i.validation", "v")
                ->join("v.typeValidation", "tv")
                ->andWhere("tv = " . $this->getTypeValidationDossier()->getId());
        
        if ($this->getIntervenant()) {
            $qb->andWhere("i = " . $this->getIntervenant()->getId());
        }
        
        return $qb;
    }
    
    /**
     * @return TypeValidation
     */
    private function getTypeValidationDossier()
    {
        $qb = $this->getServiceTypeValidation()->finderByCode(TypeValidation::CODE_DONNEES_PERSO);
        $typeValidation = $qb->getQuery()->getOneOrNullResult();
        
        return $typeValidation;
    }
    
    /**
     * 
     * @return TypeValidationService
     */
    private function getServiceTypeValidation()
    {
        return $this->getServiceLocator()->get('ApplicationTypeValidation');
    }
}