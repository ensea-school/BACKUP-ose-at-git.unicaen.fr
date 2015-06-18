<?php

namespace Application\Service\Indicateur\Service\Validation;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteValidationRefRealisePermAutreCompIndicateurImpl extends AttenteValidationRefRealisePermIndicateurImpl
{
    protected $singularTitlePattern = "%s %s a   clôturé la saisie de ses   services réalisés et est  en attente de validation de son  référentiel <em>%s</em> par d'autres composantes";
    protected $pluralTitlePattern   = "%s %s ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leur référentiel <em>%s</em> par d'autres composantes";
    
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
                    ->andWhere("f.structure <> :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        return $qb;
    }
}