<?php

namespace Application;


use UnicaenAuth\Guard\PrivilegeController;
use Zend\Config\Factory;
use Zend\Stdlib\Glob;

class ConfigFactory
{

    public static function configFromSimplified(string $dir, string $namespace): array
    {
        $paths = Glob::glob($dir . '/config/{,*.}{config}.php', Glob::GLOB_BRACE);

        $config = Factory::fromFiles($paths);

        $finalConfig = [
            'doctrine' => [
                'driver' => [
                    'orm_default_driver' => [
                        'paths' => [
                            $dir . '/src/' . $namespace . '/Entity/Db/Mapping',
                        ],
                    ],
                    'orm_default'        => [
                        'drivers' => [
                            $namespace . '\Entity\Db' => 'orm_default_driver',
                        ],
                    ],
                ],
            ],

            'view_manager' => [
                'template_path_stack' => [
                    $dir . '/view',
                ],
            ],
        ];

        if (isset($config['console'])) {
            $finalConfig['console'] = [
                'router' => [
                    'routes' => $config['console'],
                ],
            ];
        }

        if (isset($config['routes'])) {
            $finalConfig['router'] = [
                'routes' => [
                    strtolower($namespace) => $config['routes'],
                ],
            ];
        }

        if (isset($config['navigation'])) {
            $finalConfig['navigation'] = [
                'default' => [
                    'home' => [
                        'pages' => $config['navigation'],
                    ],
                ],
            ];
        }

        if (isset($config['guards'])) {
            $finalConfig['bjyauthorize'] = [
                'guards' => [
                    PrivilegeController::class => $config['guards'],
                ],
            ];
        }

        if (isset($config['controllers'])) {
            $finalConfig['controllers'] = [
                'factories' => $config['controllers'],
            ];
        }

        if (isset($config['services'])) {
            $finalConfig['service_manager'] = [
                'factories' => $config['services'],
            ];
        }

        if (isset($config['forms'])) {
            $finalConfig['form_elements'] = [
                'factories' => $config['forms'],
            ];
        }

        if (isset($config['view_helpers'])) {
            $finalConfig['view_helpers'] = [
                'factories' => $config['view_helpers'],
            ];
        }

        return $finalConfig;
    }



    public static function autoloaderConfig(string $dir, string $namespace): array
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                $dir . '/autoload_classmap.php',
            ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    $namespace => $dir . '/src/' . $namespace,
                ],
            ],
        ];
    }
}
