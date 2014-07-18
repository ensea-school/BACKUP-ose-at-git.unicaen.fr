<?php

namespace Application\Rule;

/**
 * Description of AbstractRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractRule implements RuleInterface
{
    protected $message;
    protected function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
    public function getMessage()
    {
        return $this->message;
    }
}