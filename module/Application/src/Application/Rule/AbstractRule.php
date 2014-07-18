<?php

namespace Application\Rule;

/**
 * Description of AbstractRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractRule implements RuleInterface
{
    /**
     * @var string
     */
    protected $message;
    
    /**
     * 
     * @param string $message
     * @return self
     */
    protected function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}