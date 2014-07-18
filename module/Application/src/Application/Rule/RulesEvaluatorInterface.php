<?php

namespace Application\Rule;

/**
 * Description of RuleEvaluator
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
interface RulesEvaluatorInterface
{
    public function execute();
    public function getMessage();
}