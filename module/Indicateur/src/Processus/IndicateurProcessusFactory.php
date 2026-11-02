<?php

namespace Indicateur\Processus;

use Psr\Container\ContainerInterface;
use UnicaenMail\Service\Mail\MailService;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class IndicateurProcessusFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $renderer = $container->get('ViewHelperManager')->getRenderer();

        $config = $container->get('Config');

        $scheme = $config['cli_config']['scheme'] ?? 'https';
        $domain = $config['cli_config']['domain'] ?? null;

        $host = $scheme.'://'.$domain;


        $processus = new IndicateurProcessus($renderer, $host);
        $processus->setMailService($container->get(MailService::class));

        return $processus;
    }

}