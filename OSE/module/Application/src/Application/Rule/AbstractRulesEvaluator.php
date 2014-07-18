<?php

namespace Application\Rule;

/**
 * Description of SaisieServiceRulesEvaluator
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractRulesEvaluator implements RulesEvaluatorInterface
{
    protected $rules   = array();
    protected $messages = array();
    public function isRelevant()
    {
        return true;
    }
    public function getMessage($glue = PHP_EOL)
    {
        return implode($glue, $this->getMessages());
    }
    public function getMessages()
    {
        return $this->messages;
    }
}