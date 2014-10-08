<?php

namespace Application\Rule;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of AbstractRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractRule implements RuleInterface, ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;
    
    /**
     * @var array
     */
    protected $messages = [];
    
    /**
     * 
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
    
    /**
     * 
     * @param string $message
     * @return self
     */
    protected function setMessage($message)
    {
        $this->messages = $message ? [ $message ] : [];
        
        return $this;
    }
    
    /**
     * 
     * @param array $messages
     * @return self
     */
    protected function setMessages(array $messages = array())
    {
        $this->messages = $messages;
        
        return $this;
    }
    
    /**
     * 
     * @param string $message
     * @return self
     */
    protected function addMessage($message)
    {
        $this->messages[] = $message;
        
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getMessage($glue = PHP_EOL)
    {
        if (!$this->getMessages()) {
            return null;
        }
        
        return implode($glue, $this->getMessages());
    }
}