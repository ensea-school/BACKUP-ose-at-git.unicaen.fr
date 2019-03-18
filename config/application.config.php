<?php





class AppConfig
{
    const LOCAL_APPLICATION_CONFIG_FILE = __DIR__.'/../config.local.php';

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

        if (!self::inConsole()) {
            array_unshift($modules, 'BjyAuthorize'); // ne charge BjyAuthorize QUE si on n'est pas en mode console
        }

        if ('development' == $env) {
            $modules[] = 'ZendDeveloperTools';
            $configGlobPaths[] = 'config/autoload/{,*.}{global,local}.php.dev';
        }

        if (self::inConsole() || 'development' == $env){
            $modules[] = 'UnicaenCode';
        }

        return [
            'translator'              => [
                'locale' => 'fr_FR',
            ],
            'modules'                 => $modules,
            'module_listener_options' => [
                'config_glob_paths'        => [
                    'config/autoload/{,*.}{global,local'.('development' == $env ? ',dev' : '').'}.php',
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



    private static function inConsole()
    {
        if (class_exists('Zend\Console\Console')){
            return \Zend\Console\Console::isConsole();
        }else{
            return true;
        }
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