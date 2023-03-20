<?php
/**
 * Laminas (http://framework.Laminas.com/)
 *
 * @link      http://github.com/Laminas/LaminasSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Laminas Technologies USA Inc. (http://www.Laminas.com)
 * @license   http://framework.Laminas.com/license/new-bsd New BSD License
 */

namespace ExportRh;


use Application\ConfigFactory;
use Laminas\Mvc\MvcEvent;
use Laminas\Stdlib\Glob;

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
