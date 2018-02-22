<?php

class Application
{
    const LOCAL_APPLICATION_CONFIG_FILE = 'config/application.local.php';

    /**
     * @var Zend\ServiceManager\ServiceLocatorInterface
     */
    public static $container;

    /**
     * @var bool
     */
    public static $maintenance          = false;

    /**
     * @var string
     */
    public static $maintenanceText      = 'OSE est actuellement en maintenance. Veuillez nous excuser pour ce déagrément.';

    /**
     * @var array
     */
    public static $maintenanceWhiteList = [];

    /**
     * Configuration locale de l'application
     *
     * @var bool|array
     */
    private static $config = false;



    private static function init()
    {
        \Locale::setDefault('fr_FR');
        define('REQUEST_MICROTIME', microtime(true));
        chdir(dirname(__DIR__));

        require 'init_autoloader.php';
    }



    public static function getConfig($section=null, $key = null, $default=null)
    {
        if (false === self::$config){
            if (file_exists(self::LOCAL_APPLICATION_CONFIG_FILE)) {
                self::$config = require(self::LOCAL_APPLICATION_CONFIG_FILE);
            }else{
                self::$config = null;
            }
        }

        if (self::$config && $section && $key){
            if (isset(self::$config[$section][$key])){
                return self::$config[$section][$key];
            }else{
                return $default;
            }
        }

        return self::$config;
    }



    private static function startContainerAndListeners()
    {
        if (null !== self::getConfig()){
            $configuration = require 'config/application.config.php';

            $smConfig        = isset($configuration['service_manager']) ? $configuration['service_manager'] : [];
            self::$container = new Zend\ServiceManager\ServiceManager(new Zend\Mvc\Service\ServiceManagerConfig($smConfig));
            self::$container->setService('ApplicationConfig', $configuration);
            self::$container->get('ModuleManager')->loadModules();

            $listenersFromAppConfig     = isset($configuration['listeners']) ? $configuration['listeners'] : [];
            $config                     = self::$container->get('Config');
            $listenersFromConfigService = isset($config['listeners']) ? $config['listeners'] : [];

            $listeners = array_unique(array_merge($listenersFromConfigService, $listenersFromAppConfig));
        }
        $modeInstallation = self::getConfig('global','modeInstallation', true);

        if ($modeInstallation){
            return null;
        }

        return self::$container->get('Application')->bootstrap($listeners);
    }



    private static function installation()
    {
        require 'install.php';
    }



    private static function maintenance()
    {
        if (!self::getConfig('maintenance','modeMaintenance', true)){
            return false;
        }

        $whiteList = self::getConfig('maintenance','whiteList',[]);
        self::$maintenanceText = self::getConfig('maintenance', 'messageInfo');

        if (php_sapi_name() === 'cli') {
            exit(0);
        }

        $passed = false;
        foreach ($whiteList as $ip) {
            $passed = $ip[0] === $_SERVER['REMOTE_ADDR'];
            if ($passed && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $passed = isset($ip[1]) && $ip[1] === $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            if ($passed) break;
        }
        if (!$passed) {
            include 'maintenance.php';

            return true;
        }

        return false;
    }



    public static function run()
    {
        self::init();
        if (!self::maintenance()) {
            if ($applicationModule = self::startContainerAndListeners()){
                $applicationModule->run();
            }else{
                self::installation();
            }
        }
    }
}



Application::run();