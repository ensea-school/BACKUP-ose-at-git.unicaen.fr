<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use UnicaenMail\Service\Mail\MailService;

class MailServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return MailService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : MailService
    {
        $config = $container->get('Configuration')['unicaen-mail'];

        /* Injection de l'utilisateur courant pour le redirect de mails */
        $context = $container->get(ContextService::class);
        $email = $context->getUtilisateur()?->getEmail();
        $redirectTo = $config['module']['default']['redirect_to'];
        if ($email && !empty($redirectTo)) {
            $config['module']['default']['redirect_to'] = $email;
        }


        $transport = new EsmtpTransport(host: $config['transport_options']['host'], port: $config['transport_options']['port']);
        $mailer = new Mailer($transport);

        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new MailService();
        $service->setMailer($mailer);
        $service->setConfig($config);
        $service->setEntityClass($config['mail_entity_class']);
        $service->setObjectManager($entityManager);
        return $service;
    }
}