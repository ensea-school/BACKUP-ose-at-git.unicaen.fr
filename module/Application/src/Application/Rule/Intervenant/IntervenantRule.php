<?php

namespace Application\Rule\Intervenant;

use Application\Rule\AbstractRule;
use Application\Entity\Db\Intervenant;

/**
 * Description of ServiceRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class IntervenantRule extends AbstractRule
{
    protected $intervenant;
    public function __construct(Intervenant $intervenant)
    {
        $this->intervenant = $intervenant;
    }
    public function getIntervenant()
    {
        return $this->intervenant;
    }
}