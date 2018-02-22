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
    public static $maintenance = false;

    /**
     * @var string
     */
    public static $maintenanceText = 'OSE est actuellement en maintenance. Veuillez nous excuser pour ce déagrément.';

    /**
     * @var array
     */
    public static $maintenanceWhiteList = [];

    /**
     * Configuration locale de l'application
     *
     * @var array
     */
    private static $config;



    private static function init()
    {
        \Locale::setDefault('fr_FR');
        define('REQUEST_MICROTIME', microtime(true));
        chdir(dirname(__DIR__));

        if (file_exists(self::LOCAL_APPLICATION_CONFIG_FILE)) {
            self::$config = require(self::LOCAL_APPLICATION_CONFIG_FILE);
        } else {
            self::$config = null;
        }

        require 'init_autoloader.php';
    }



    public static function getConfig($section = null, $key = null, $default = null)
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



    private static function startContainerAndListeners()
    {
        if (null === self::getConfig()) return null;
        $configuration = require 'config/application.config.php';

        $smConfig        = isset($configuration['service_manager']) ? $configuration['service_manager'] : [];
        self::$container = new Zend\ServiceManager\ServiceManager(new Zend\Mvc\Service\ServiceManagerConfig($smConfig));
        self::$container->setService('ApplicationConfig', $configuration);
        self::$container->get('ModuleManager')->loadModules();

        $listenersFromAppConfig     = isset($configuration['listeners']) ? $configuration['listeners'] : [];
        $config                     = self::$container->get('Config');
        $listenersFromConfigService = isset($config['listeners']) ? $config['listeners'] : [];

        $listeners = array_unique(array_merge($listenersFromConfigService, $listenersFromAppConfig));

        return $listeners;
    }



    private static function inMaintenance()
    {
        $maintenance = self::getConfig('maintenance', 'modeMaintenance', false);

        if (!$maintenance) return false;

        $whiteList             = self::getConfig('maintenance', 'whiteList', []);

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
        return !$passed;
    }



    public static function run()
    {
        self::init();

        if (self::inMaintenance()){
            self::$maintenanceText = self::getConfig('maintenance', 'messageInfo');
            require 'maintenance.php';
        }else{
            $listeners = self::startContainerAndListeners();

            if (($listeners === null) || self::getConfig('global', 'modeInstallation', true)){
                require 'install.php';
            }else{
                self::$container->get('Application')->bootstrap($listeners)->run();
            }
        }
    }
}





Application::run();