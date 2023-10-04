<?php

namespace Lieu;

use Application\ConfigFactory;

class Module
{

    public function getConfig()
    {
        return ConfigFactory::configFromSimplified(__DIR__, __NAMESPACE__);
    }



    public function getAutoloaderConfig()
    {
        return ConfigFactory::autoloaderConfig(__DIR__, __NAMESPACE__);
    }

}
