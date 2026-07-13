<?php

namespace Administration\Command;

use Psr\Container\ContainerInterface;
use UnicaenMail\Service\Mail\MailService;

/**
 * Factory de la commande de vérification de l'espace disque.
 */
class VerifierEspaceDisqueCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return VerifierEspaceDisqueCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): VerifierEspaceDisqueCommand
    {
        $command = new VerifierEspaceDisqueCommand;
        $command->setMailService($container->get(MailService::class));

        return $command;
    }
}
