<?php

namespace Chargens;

use Application\ConfigFactory;

class Module
{

    public function getConfig()
    {
        return ConfigFactory::configFromSimplified(dirname(__DIR__), __NAMESPACE__);
    }

}
