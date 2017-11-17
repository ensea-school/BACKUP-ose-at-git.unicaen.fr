<?php





class Application
{
    /**
     * @var Zend\ServiceManager\ServiceLocatorInterface
     */
    public static $container;

    /**
     * @var bool
     */
    public static $maintenance          = false;

    public static $maintenanceText      = "
    Ose est actuellement en cours de mise à jour. 
    L'opération devrait être terminée dans l'après-midi. 
    Veuillez nous excuser pour ce déagrément.
    ";

    public static $maintenanceWhiteList = [
        ['127.0.0.1'], // localhost
        ['10.26.24.16'], // Olivier
        ['10.26.4.17'], // Laurent
        ['10.26.24.39'], // Anthony
    ];



    private static function init()
    {
        \Locale::setDefault('fr_FR');
        define('REQUEST_MICROTIME', microtime(true));
        chdir(dirname(__DIR__));

        require 'init_autoloader.php';
    }



    private static function startContainerAndListeners()
    {
        $configuration = require 'config/application.config.php';

        $smConfig        = isset($configuration['service_manager']) ? $configuration['service_manager'] : [];
        self::$container = new Zend\ServiceManager\ServiceManager(new Zend\Mvc\Service\ServiceManagerConfig($smConfig));
        self::$container->setService('ApplicationConfig', $configuration);
        self::$container->get('ModuleManager')->loadModules();

        $listenersFromAppConfig     = isset($configuration['listeners']) ? $configuration['listeners'] : [];
        $config                     = self::$container->get('Config');
        $listenersFromConfigService = isset($config['listeners']) ? $config['listeners'] : [];

        $listeners = array_unique(array_merge($listenersFromConfigService, $listenersFromAppConfig));

        return self::$container->get('Application')->bootstrap($listeners);
    }



    private static function maintenance()
    {
        if (!self::$maintenance) return false;

        if (php_sapi_name() === 'cli') {
            exit(0);
        }

        $passed = false;
        foreach (self::$maintenanceWhiteList as $ip) {
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
            $applicationModule = self::startContainerAndListeners();
            $applicationModule->run();
        }
    }
}





Application::run();