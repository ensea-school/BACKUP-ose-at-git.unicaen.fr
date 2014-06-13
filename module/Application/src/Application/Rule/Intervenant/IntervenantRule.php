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
    /**
     * @var Intervenant 
     */
    protected $intervenant;
    
    /**
     * Constructeur.
     * 
     * @param Intervenant $intervenant Intervenant concerné
     */
    public function __construct(Intervenant $intervenant)
    {
        $this->intervenant = $intervenant;
    }
    
    /**
     * Retourne l'intervenant concerné.
     * 
     * @return Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }
}