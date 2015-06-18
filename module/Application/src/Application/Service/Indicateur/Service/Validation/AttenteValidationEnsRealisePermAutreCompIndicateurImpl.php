<?php

namespace Application\Service\Indicateur\Service\Validation;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteValidationEnsRealisePermAutreCompIndicateurImpl extends AttenteValidationEnsRealisePermIndicateurImpl
{
    protected $singularTitlePattern = "%s %s a   clôturé la saisie de ses   services réalisés et est  en attente de validation de ses   enseignements <em>%s</em> par d'autres composantes";
    protected $pluralTitlePattern   = "%s %s ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leurs enseignements <em>%s</em> par d'autres composantes";
    
    /**
     * Témoin indiquant s'il faut appliquer le filtre Structure.
     * 
     * @var boolean
     */
    protected $findByStructure = false;
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $qb = parent::getQueryBuilder();
        
        /**
         * Toute autre composante que celle spécifiée.
         */
        if ($this->getStructure()) {
            $qb
                    ->andWhere("ep.structure <> :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        return $qb;
    }
}