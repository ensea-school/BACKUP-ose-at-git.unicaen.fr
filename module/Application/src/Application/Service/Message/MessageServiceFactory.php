<?php
/**
 * Created by PhpStorm.
 * User: gauthierb
 * Date: 16/07/15
 * Time: 10:45
 */

namespace Application\Service\Message;


use Application\Service\Traits\ContextAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MessageServiceFactory implements FactoryInterface
{
    use ContextAwareTrait;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return MessageService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $this->getConfig($serviceLocator);
        $messageRepository = new MessageRepository($config);

        $role = $serviceLocator->get('ApplicationContext')->getSelectedIdentityRole();

        $service = new MessageService($messageRepository);
        $service->setRole($role);

        return $service;
    }

    private function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        if (! isset($config['message']['messages'])) {
            throw new RuntimeException("Aucune configuration des messages n'a été trouvée.");
        }

        return $config['message']['messages'];
    }
}