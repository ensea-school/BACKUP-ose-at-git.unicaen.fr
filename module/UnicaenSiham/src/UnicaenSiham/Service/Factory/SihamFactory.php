<?php

namespace UnicaenSiham\Service\Factory;

use Psr\Container\ContainerInterface;
use UnicaenSiham\Service\Siham;
use UnicaenSiham\Service\SihamClient;

class SihamFactory
{
    public function __invoke(ContainerInterface $container): Siham
    {
        $sihamClient = $container->get(SihamClient::class);
        $configSiham = $container->get('Config');
        $config      = $configSiham['unicaen-siham'];

        return new Siham($sihamClient, $config);
    }

    /*    public function __invoke(ContainerInterface $container): SoapClient
        {
            
            $moduleConfig = $container->get(ModuleConfig::class);
    
            $soapClientConfig = $moduleConfig->getSoapClientConfig();
    
            return new SoapClient($soapClientConfig);
        }
    
    
    
    
    
    */
}
