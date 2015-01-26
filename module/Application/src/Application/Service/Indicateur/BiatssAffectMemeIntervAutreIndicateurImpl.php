<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\StatutIntervenant;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class BiatssAffectMemeIntervAutreIndicateurImpl extends VacatAffectMemeIntervAutreAbstractIndicateurImpl
{
    protected $codeStatutIntervenant = StatutIntervenant::BIATSS;
}