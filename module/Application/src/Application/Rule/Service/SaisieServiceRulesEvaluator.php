<?php

namespace Application\Rule\Service;

use Application\Rule\AbstractRulesEvaluator;
use Application\Entity\Db\Intervenant;
use Application\Rule\Intervenant\PossedeDossierRule;
use Application\Rule\Intervenant\PeutSaisirServiceRule;

/**
 * Description of SaisieServiceRulesEvaluator
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class SaisieServiceRulesEvaluator extends AbstractRulesEvaluator
{
    public function __construct(Intervenant $intervenant)
    {
        $this->rules = [
            new PossedeDossierRule($intervenant),
            new PeutSaisirServiceRule($intervenant),
        ];
    }
    public function execute()
    {
        foreach ($this->rules as $rule) { /* @var $rule Rule */
            if (!$rule->isRelevant()) {
                continue;
            }
            if (!$rule->execute()) {
                $this->messages[] = $rule->getMessage();
                return false;
            }
        }
        return true;
    }
    public function isRelevant()
    {
        return true;
    }
}