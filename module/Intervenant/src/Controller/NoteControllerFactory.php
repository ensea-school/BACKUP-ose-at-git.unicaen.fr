<?php

namespace Intervenant\Controller;

use Psr\Container\ContainerInterface;


/**
 * Description of NoteController
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class NoteControllerFactory
{

    public function __invoke(ContainerInterface $container, $requestedName, $options = null): NoteController
    {
        $controller = new NoteController();


        return $controller;
    }
}