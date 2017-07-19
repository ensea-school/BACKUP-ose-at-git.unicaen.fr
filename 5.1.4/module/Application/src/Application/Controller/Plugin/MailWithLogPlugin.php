<?php
namespace Application\Controller\Plugin;

use Zend\Log\Logger;
use UnicaenApp\Controller\Plugin\Mail;

/**
 * Description of Mail
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class MailWithLogPlugin extends Mail
{
    protected $logger;
    
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
        return $this;
    }
    
    /**
     * Envoit le message.
     * 
     * @param \Zend\Mail\Message $message Message à envoyer
     * @return \Zend\Mail\Message Message effectivement envoyé, différent de l'original si la redirection est activée
     */
    public function send(\Zend\Mail\Message $message)
    {
        if ($this->logger) {
            $template = <<<EOS
Will send message :
................................................................................
%s
................................................................................
                    
                    
                    
EOS;
            $this->logger->info(sprintf($template, $message->toString()));
        }
        
        return parent::send($message);
    }
}