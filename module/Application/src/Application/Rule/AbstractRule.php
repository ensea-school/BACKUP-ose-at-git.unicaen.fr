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
//    protected $messages = [];
    
    protected $abstractOptions = array(
        'messages'             => array(), // Array of validation failure messages
        'messageTemplates'     => array(), // Array of validation failure message templates
        'messageVariables'     => array(), // Array of additional variables available for validation failure messages
//        'translator'           => null,    // Translation object to used -> Translator\TranslatorInterface
//        'translatorTextDomain' => null,    // Translation text domain
//        'translatorEnabled'    => true,    // Is translation enabled?
//        'valueObscured'        => false,   // Flag indicating whether or not value should be obfuscated
                                           // in error messages
    );
    
    /**
     * Constructeur.
     */
    public function __construct()
    {
        if (isset($this->messageTemplates)) {
            $this->abstractOptions['messageTemplates'] = $this->messageTemplates;
        }

        if (isset($this->messageVariables)) {
            $this->abstractOptions['messageVariables'] = $this->messageVariables;
        }
    }
    
    /**
     * Returns array of messages
     * 
     * @return array
     */
    public function getMessages()
    {
        return array_unique($this->abstractOptions['messages'], SORT_REGULAR);
    }

    /**
     * Sets message templates given as an array, where the array keys are the message keys,
     * and the array values are the message template strings.
     *
     * @param  array $messages
     * @return self
     */
    public function setMessages(array $messages)
    {
        foreach ($messages as $key => $message) {
            $this->setMessage($message, $key);
        }
        return $this;
    }

    /**
     * Sets the message template for a particular key
     *
     * @param  string $messageString
     * @param  string $messageKey     OPTIONAL
     * @return self Provides a fluent interface
     * @throws Exception\InvalidArgumentException
     */
    public function setMessage($messageString, $messageKey = null)
    {
        if ($messageKey === null) {
            $keys = array_keys($this->abstractOptions['messageTemplates']);
            foreach ($keys as $key) {
                $this->setMessage($messageString, $key);
            }
            return $this;
        }

        if (!isset($this->abstractOptions['messageTemplates'][$messageKey])) {
            throw new \InvalidArgumentException("No message template exists for key '$messageKey'");
        }

        $this->abstractOptions['messageTemplates'][$messageKey] = $messageString;
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

    /**
     * @param  string              $messageKey
     * @param  string|array|object $value
     * @return void
     */
    protected function message($messageKey = null, $value = null)
    {
        if ($messageKey === null) {
            $this->abstractOptions['messages'] = [];
        }
        else {
            $this->abstractOptions['messages'][$messageKey] = $this->createMessage($messageKey, $value);
        }
    }

    /**
     * Constructs and returns a validation failure message with the given message key and value.
     *
     * Returns null if and only if $messageKey does not correspond to an existing template.
     *
     * If a translator is available and a translation exists for $messageKey,
     * the translation will be used.
     *
     * @param  string              $messageKey
     * @param  string|array|object $value
     * @return string
     */
    protected function createMessage($messageKey, $value)
    {
        if (!isset($this->abstractOptions['messageTemplates'][$messageKey])) {
            return null;
        }

        $message = $this->abstractOptions['messageTemplates'][$messageKey];

        if (is_object($value) &&
            !in_array('__toString', get_class_methods($value))
        ) {
            $value = get_class($value) . ' object';
        } elseif (is_array($value)) {
            $value = var_export($value, 1);
        } else {
            $value = (string) $value;
        }

        $message = str_replace('%value%', (string) $value, $message);
        foreach ($this->abstractOptions['messageVariables'] as $ident => $property) {
            if (is_array($property)) {
                $value = $this->{key($property)}[current($property)];
                if (is_array($value)) {
                    $value = '[' . implode(', ', $value) . ']';
                }
            } else {
                $value = $this->$property;
            }
            $message = str_replace("%$ident%", (string) $value, $message);
        }

        return $message;
    }
}