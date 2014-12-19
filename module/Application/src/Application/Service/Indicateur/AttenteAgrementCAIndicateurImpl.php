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
}