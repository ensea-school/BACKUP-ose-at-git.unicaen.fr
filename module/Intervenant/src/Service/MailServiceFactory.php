<?php

namespace Intervenant\Service;

use Psr\Container\ContainerInterface;


/**
 * Description of StatutServiceFactory
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class MailServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MailService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $mail = $container->get('ControllerPluginManager')->get('mail');

        $service = new MailService($mail);

        return $service;
    }
}