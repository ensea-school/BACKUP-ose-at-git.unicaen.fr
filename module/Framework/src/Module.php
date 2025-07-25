<?php

namespace Framework;

use Application\ConfigFactory;

class Module
{

    public function getConfig(): array
    {
        return ConfigFactory::configFromSimplified(dirname(__DIR__), __NAMESPACE__);
    }

}