<?php

namespace Application\Controller\Plugin;

use UnicaenApp\Controller\Plugin\Mail;
use UnicaenApp\Options\ModuleOptions;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\ServiceManager\Exception\InvalidArgumentException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of MailFactory
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class MailWithLogPluginFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $pluginManager
     * @return Mail
     */
    public function createService(ServiceLocatorInterface $pluginManager)
    {
        $options     = $pluginManager->getServiceLocator()->get('unicaen-app_module_options'); /* @var $options ModuleOptions */
        $mailOptions = $options->getMail();
        
        if (!isset($mailOptions['transport_options'])) {
            throw new InvalidArgumentException("Options de transport de mail introuvables.");
        }
        
        $transport = new Smtp(new SmtpOptions($mailOptions['transport_options']));
        $plugin    = new MailWithLogPlugin($transport);
        
        if (isset($mailOptions['redirect_to'])) {
            $plugin->setRedirectTo($mailOptions['redirect_to']);
        }
        if (isset($mailOptions['do_not_send'])) {
            $plugin->setDoNotSend($mailOptions['do_not_send']);
        }
        
        $logger = new Logger();
        $logger->addWriter(new Stream(getcwd() . "/data/mail.log"));
        $plugin->setLogger($logger);
        
        return $plugin;
    }
}