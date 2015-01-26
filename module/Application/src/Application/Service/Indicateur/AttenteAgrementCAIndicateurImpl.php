<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\TypeAgrement;
use Application\Entity\Db\WfEtape;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteAgrementCAIndicateurImpl extends AttenteAgrementAbstractIndicateurImpl
{
    protected $codeTypeAgrement = TypeAgrement::CODE_CONSEIL_ACADEMIQUE;
    protected $codeEtape        = WfEtape::CODE_CONSEIL_ACADEMIQUE;
    
    /**
     * Surcharge pour ne renvoyer aucune structure car l'agrément CA
     * n'est pas donné par une composante d'enseignement en particulier.
     * 
     * @return null
     */
    public function getStructure()
    {
        return null;
    }
}