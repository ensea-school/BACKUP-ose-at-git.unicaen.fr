<?php

namespace Intervenant\Controller;

use Psr\Container\ContainerInterface;
use UnicaenMail\Service\Mail\MailService;



/**
 * Description of NoteController
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class NoteControllerFactory
{

    public function __invoke(ContainerInterface $container, $requestedName, $options = null): NoteController
    {
        $noteController = new NoteController();
        $noteController->setMailService($container->get(MailService::class));

        return $noteController;
    }
}