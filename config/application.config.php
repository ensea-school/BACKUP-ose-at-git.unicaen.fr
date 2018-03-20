<?php





class AppConfig
{
    const LOCAL_APPLICATION_CONFIG_FILE = 'config/application.local.php';

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
        if (file_exists(self::LOCAL_APPLICATION_CONFIG_FILE)) {
            self::$config = require(self::LOCAL_APPLICATION_CONFIG_FILE);
        } else {
            self::$config = null;
        }
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

        return self::$config;
    }



    public static function getEnv()
    {
        return getenv('APPLICATION_ENV') ?: 'dev';
    }



    private static function makeGlobal()
    {
        $env = self::getEnv();

        $modules = [
            'ZfcBase', 'DoctrineModule', 'DoctrineORMModule', 'ZfcUser', 'ZfcUserDoctrineORM',
            'UnicaenApp', 'UnicaenAuth', 'UnicaenImport', 'UnicaenTbl',
            'Application',
        ];

        if (!\Zend\Console\Console::isConsole()) {
            array_unshift($modules, 'BjyAuthorize'); // ne charge BjyAuthorize QUE si on n'est pas en mode console
        }

        if ('development' == $env) {
            $modules[] = 'ZendDeveloperTools';
        }

        if (\Zend\Console\Console::isConsole() || 'development' == $env){
            $modules[] = 'UnicaenCode';
        }

        return [
            'translator'              => [
                'locale' => 'fr_FR',
            ],
            'modules'                 => $modules,
            'module_listener_options' => [
                'config_glob_paths'        => [
                    'config/autoload/{,*.}{global,local}.php',
                ],
                'module_paths'             => [
                    './module',
                    './vendor',
                ],
                'cache_dir'                => 'data/cache/',
                'config_cache_enabled'     => ('production' == $env),
                'module_map_cache_enabled' => ('production' == $env),
            ],
        ];
    }



    public static function getGlobal()
    {
        if (!self::$global){
            self::$global = self::makeGlobal();
        }
        return self::$global;
    }
}





AppConfig::init();
return AppConfig::getGlobal();