<?php

namespace Application\Rule\Intervenant;

use Application\Rule\AbstractRule;
use Application\Entity\Db\Intervenant;
use Application\Traits\IntervenantAwareTrait;

/**
 * Description of ServiceRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class IntervenantRule extends AbstractRule
{
    use IntervenantAwareTrait;
    
    /**
     * Constructeur.
     * 
     * @param Intervenant $intervenant Intervenant concerné
     */
    public function __construct(Intervenant $intervenant)
    {
        $this->setIntervenant($intervenant);
    }
}