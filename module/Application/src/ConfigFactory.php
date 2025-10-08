<?php

namespace Application;

use Laminas\Config\Factory;
use Laminas\Stdlib\Glob;

class ConfigFactory
{

    public static function configFromSimplified(string $dir, string $namespace): array
    {
        $paths = Glob::glob($dir . '/config/{,*.}{config}.php', Glob::GLOB_BRACE);

        $config      = Factory::fromFiles($paths);

        $finalConfig = [];

        if (file_exists($dir . '/src/Entity/Db/Mapping')) {
            $finalConfig['doctrine'] = [
                'driver' => [
                    'orm_default_driver' => [
                        'paths' => [
                            $dir . '/src/Entity/Db/Mapping',
                        ],
                    ],
                    'orm_default'        => [
                        'drivers' => [
                            $namespace . '\Entity\Db' => 'orm_default_driver',
                        ],
                    ],
                ],
            ];
        }

        $finalConfig['view_manager'] = [
            'template_path_stack' => [
                $namespace => $dir . '/view',
            ],
            'template_map'        => include $dir . '/template_map.php',
        ];

        if (isset($config['routes'])) {
            $finalConfig['router']['routes'] = [];
            foreach ($config['routes'] as $cr => $cc) {
                $finalConfig['router']['routes'][$cr] = self::routeSimplified($cc, false);
            }
            unset($config['routes']);
        }

        if (isset($config['navigation'])) {
            $finalConfig['navigation'] = [
                'default' => [
                    'home' => [
                        'pages' => $config['navigation'],
                    ],
                ],
            ];
            unset($config['navigation']);
        }

        if (isset($config['controllers'])) {
            $finalConfig['controllers'] = [
                'factories' => $config['controllers'],
            ];
            unset($config['controllers']);
        }

        if (isset($config['services'])) {
            $finalConfig['service_manager'] = [
                'factories' => $config['services'],
            ];
            unset($config['services']);
        }

        if (isset($config['forms'])) {
            $finalConfig['form_elements'] = [
                'factories' => $config['forms'],
            ];
            unset($config['forms']);
        }

        if (isset($config['view_helpers'])) {
            $finalConfig['view_helpers'] = [
                'factories' => $config['view_helpers'],
            ];
            unset($config['view_helpers']);
        }

        if (!empty($config)){
            // on réintègre tout ce qui reste
            foreach( $config as $k => $v ){
                $finalConfig[$k] = $v;
            }
        }

        return $finalConfig;
    }



    public static function routeSimplified(array $config, bool $isConsole = false): array
    {
        /* On remonte ces params dans le sous-menu options */
        $optionsParams = ['route', 'defaults', 'constraints'];
        foreach ($optionsParams as $param) {
            if (isset($config[$param]) && !isset($config['options'][$param])) {
                if (!isset($config['options'])) $config['options'] = [];
                $config['options'][$param] = $config[$param];
                unset($config[$param]);
            }
        }

        /* on remonte controller et action dans options/default */
        $defaultParams = ['controller', 'action'];
        foreach ($defaultParams as $param) {
            if (isset($config[$param]) && !isset($config['options']['defaults'][$param])) {
                if (!isset($config['options'])) $config['options'] = [];
                if (!isset($config['options']['defaults'])) $config['options']['defaults'] = [];
                $config['options']['defaults'][$param] = $config[$param];
                unset($config[$param]);
            }
        }

        /* On détecte le type s'il n'existe pas déjà */
        if (!isset($config['type']) && isset($config['options']['route']) && !$isConsole) {
            if (false !== strpos($config['options']['route'], ':')) {
                $config['type'] = 'Segment';
            } else {
                $config['type'] = 'Literal';
            }
        }

        /* Si il y a des routes filles, on les parse aussi */
        if (isset($config['child_routes'])) {
            foreach ($config['child_routes'] as $sRoute => $sConfig) {
                $config['child_routes'][$sRoute] = self::routeSimplified($sConfig);
            }
        }

        return $config;
    }
}
