<?php

namespace Indicateur\Controller;

use Psr\Container\ContainerInterface;
use Laminas\View\Renderer\PhpRenderer;
use UnicaenMail\Service\Mail\MailService;

class IndicateurControllerFactory
{
    /**
     * @param ContainerInterface $container
     * @param                    $requestedName
     * @param null               $options
     *
     * @return IndicateurController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $httpRouter = $container->get('HttpRouter');
        $renderer   = $container->get(PhpRenderer::class);
        $cliConfig  = $this->getCliConfig($container);

        $controller = new IndicateurController($httpRouter, $renderer, $cliConfig);
        $controller->setMailService($container->get(MailService::class));

        return $controller;
    }



    /**
     * @param ContainerInterface $container
     *
     * @return array
     */
    private function getCliConfig(ContainerInterface $container)
    {
        $config = $container->get('Config');

        return [
            'domain' => isset($config['cli_config']['domain']) ? $config['cli_config']['domain'] : null,
            'scheme' => isset($config['cli_config']['scheme']) ? $config['cli_config']['scheme'] : null,
        ];
    }

}