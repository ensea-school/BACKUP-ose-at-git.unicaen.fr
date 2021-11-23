<?php
/**
 * Laminas (http://framework.Laminas.com/)
 *
 * @link      http://github.com/Laminas/LaminasSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Laminas Technologies USA Inc. (http://www.Laminas.com)
 * @license   http://framework.Laminas.com/license/new-bsd New BSD License
 */

namespace ExportRh;


use Laminas\Mvc\MvcEvent;
use Laminas\Stdlib\Glob;
use Laminas\Config\Factory as ConfigFactory;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
    }



    public function getConfig()
    {
        $paths = Glob::glob(__DIR__ . '/config/{,*.}{config}.php', Glob::GLOB_BRACE);

        return ConfigFactory::fromFiles($paths);
    }



    public function getAutoloaderConfig()
    {
        return [
            'Laminas\Loader\ClassMapAutoloader' => [
                __DIR__ . '/autoload_classmap.php',
            ],
            'Laminas\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src',
                ],
            ],
        ];
    }

}
