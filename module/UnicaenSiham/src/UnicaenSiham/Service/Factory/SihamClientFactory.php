<?php

namespace UnicaenSiham\Service\Factory;

use Psr\Container\ContainerInterface;
use UnicaenSiham\Service\SihamClient;

class SihamClientFactory
{
    public function __invoke(ContainerInterface $container): SihamClient
    {
        $config     = $container->get('Config');
        $soapParams = $config['unicaen-siham']['soap_client']['params'];
        $wsdl       = $config['unicaen-siham']['api']['wsdl'];


        return new SihamClient($wsdl, $soapParams);
    }

}
