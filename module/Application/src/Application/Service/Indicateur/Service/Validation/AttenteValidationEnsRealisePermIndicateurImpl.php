<?php

namespace Application\Service\Indicateur\Service\Validation;

use Application\Entity\Db\TypeIntervenant as TypeIntervenantEntity;
use Application\Entity\Db\TypeValidation as TypeValidationEntity;
use Application\Service\Indicateur\Service\Validation\AttenteValidationEnsRealiseAbstractIndicateurImpl;
use Doctrine\ORM\Query\Expr\Join;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteValidationEnsRealisePermIndicateurImpl extends AttenteValidationEnsRealiseAbstractIndicateurImpl
{
    protected $singularTitlePattern = "%s %s a   clôturé la saisie de ses   services réalisés et est  en attente de validation de ses   enseignements <em>%s</em>";
    protected $pluralTitlePattern   = "%s %s ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leurs enseignements <em>%s</em>";
    
    /**
     * Témoin indiquant s'il faut que l'intervenant soit à l'étape concernée dans le WF pour être acceptable.
     * 
     * @var boolean
     */
    protected $findByWfEtapeCourante = false;
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        /**
         * Le réalisé doit être cloturé.
         */
        $qb = parent::getQueryBuilder()
                ->join("int.validation", "v")
                ->join("v.typeValidation", "tv", Join::WITH, "tv.code = :tvCode")
                ->setParameter('tvCode', TypeValidationEntity::CODE_CLOTURE_REALISE);
        
        return $qb;
    }
    
    /**
     * Retourne le type d'intervenant utile à cet indicateur.
     * 
     * @return TypeIntervenantEntity
     */
    public function getTypeIntervenant()
    {
        if (null === $this->typeIntervenant) {
            $this->typeIntervenant = 
                    $this->getServiceLocator()->get('ApplicationTypeIntervenant')->getByCode(TypeIntervenantEntity::CODE_PERMANENT);
        }
        
        return $this->typeIntervenant;
    }
}