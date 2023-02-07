<?php


include_once dirname(__DIR__) . '/module/Application/Application.php';





class AppConfig
{
    const LOCAL_APPLICATION_CONFIG_FILE = __DIR__ . '/../config.local.php';

    /**
     * Configuration locale de l'application
     *
     * @var array
     */
    private static $config;

    /**
     * Configuration globale de l'application
     *
     * @var array
     */
    private static $global;



    public static function init()
    {
        if (self::hasLocalConfig()) {
            self::$config = require(self::LOCAL_APPLICATION_CONFIG_FILE);
        } else {
            self::$config = ['global' => ['modeInstallation' => true]];
        }
    }



    public static function hasLocalConfig(): bool
    {
        return file_exists(self::LOCAL_APPLICATION_CONFIG_FILE);
    }



    public static function get($section = null, $key = null, $default = null)
    {
        if (self::$config && $section && $key) {
            if (isset(self::$config[$section][$key])) {
                return self::$config[$section][$key];
            } else {
                return $default;
            }
        }

        if (self::$config && $section) {
            if (isset(self::$config[$section])) {
                return self::$config[$section];
            }
        }

        return self::$config;
    }



    public static function getEnv()
    {
        return getenv('APPLICATION_ENV') ?: 'dev';
    }



    public static function inDev()
    {
        return 'dev' == self::getEnv();
    }



    public static function inTest()
    {
        return 'test' == self::getEnv();
    }



    public static function inProd()
    {
        return 'prod' == self::getEnv();
    }



    private static function makeGlobal()
    {
        $env = self::getEnv();

        $modules = [
            'Laminas\Cache',
            'Laminas\Filter',
            'Laminas\Form',
            'Laminas\Hydrator',
            'Laminas\I18n',
            'Laminas\InputFilter',
            'Laminas\Log',
            'Laminas\Mail',
            'Unicaen\Console',
            'Laminas\Mvc\I18n',
            'Laminas\Mvc\Plugin\FlashMessenger',
            'Laminas\Mvc\Plugin\Prg',
            'Laminas\Navigation',
            'Laminas\Paginator',
            'Laminas\Router',
            'Laminas\Session',
            'Laminas\Validator',
            'DoctrineModule',
            'DoctrineORMModule',
            'ZfcUser',
            'UnicaenApp',
            'UnicaenAuth',
            'UnicaenImport',
            'UnicaenTbl',
            'UnicaenSiham',
            'Application',
            'Intervenant',
            'Service',
            'Enseignement',
            'Referentiel',
            'Mission',
            'Paiement',
            'Plafond',
            'Indicateur',
            'ExportRh',
        ];

        if (!self::inConsole()) {
            array_unshift($modules, 'BjyAuthorize'); // ne charge BjyAuthorize QUE si on n'est pas en mode console
        }

        if (self::inDev()) {
            $modules[] = 'Laminas\DeveloperTools';
        }

        if (self::inConsole() || self::inDev()) {
            $modules[] = 'UnicaenCode';
        }

        return [
            'translator'              => [
                'locale' => 'fr_FR',
            ],
            'modules'                 => $modules,
            'module_listener_options' => [
                'config_glob_paths'        => [
                    'config/autoload/{,*.}{global,local' . (self::inDev() ? ',dev' : '') . '}.php',
                ],
                'module_paths'             => [
                    './module',
                    './vendor',
                ],
                'cache_dir'                => 'cache/',
                'config_cache_enabled'     => (self::inProd() && !self::inConsole()),
                'module_map_cache_enabled' => (self::inProd() && !self::inConsole()),
            ],
        ];
    }



    private static function inConsole()
    {
        return PHP_SAPI == 'cli';
    }



    public static function getGlobal()
    {
        if (!self::$global) {
            self::$global = self::makeGlobal();
        }

        return self::$global;
    }
}





AppConfig::init();

return AppConfig::getGlobal();
