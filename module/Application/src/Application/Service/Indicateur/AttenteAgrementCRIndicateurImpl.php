<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\TypeAgrement;
use Application\Entity\Db\WfEtape;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteAgrementCRIndicateurImpl extends AttenteAgrementAbstractIndicateurImpl
{
    protected $codeTypeAgrement = TypeAgrement::CODE_CONSEIL_RESTREINT;
    protected $codeEtape        = WfEtape::CODE_CONSEIL_RESTREINT;
    
    protected function getQueryBuilder()
    {
        $qb = parent::getQueryBuilder();
        
        /**
         * L'étape "Agrément du CR" du WF est déclinée par structure d'intervention dans la progression.
         */
        if ($this->getStructure()) {
            $qb
                    ->andWhere("p.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        return $qb;
    }
}
