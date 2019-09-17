<?php





class Application
{
    /**
     * @var Interop\Container\ContainerInterface
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



    public static function init()
    {
        define('REQUEST_MICROTIME', microtime(true));
        chdir(dirname(__DIR__));

        /* Chargement de la config globale */
        require_once 'config/application.config.php';

        /* Définition de la config globale, éventuellement à partir du fichier de config général */
        if (AppConfig::get('global', 'affichageErreurs')) {
            error_reporting(E_ALL);
        }else{
            set_exception_handler(function ($e) { // on affiche quand même les erreurs fatales pour expliquer!
                self::error($e);
            });
        }

        \Locale::setDefault(AppConfig::get('global', 'locale'));
        putenv("NLS_LANGUAGE=FRENCH");

        /* Chargement de l'autoloader */
        if (file_exists('vendor/autoload.php')) {
            include 'vendor/autoload.php';
        }

        if (!class_exists('Zend\Loader\AutoloaderFactory')) {
            throw new RuntimeException('Unable to load ZF3. Run `php composer.phar install` or define a ZF3_PATH environment variable.');
        }
    }



    private static function zendApplicationStart()
    {
        $configuration = AppConfig::getGlobal();

        //Zend\Mvc\Application::init(AppConfig::getGlobal())->run();

        // Prepare the service manager
        $smConfig = isset($configuration['service_manager']) ? $configuration['service_manager'] : [];
        $smConfig = new \Zend\Mvc\Service\ServiceManagerConfig($smConfig);

        $serviceManager = new Zend\ServiceManager\ServiceManager();
        self::$container = $serviceManager;
        $smConfig->configureServiceManager($serviceManager);
        $serviceManager->setService('ApplicationConfig', $configuration);

        // Load modules
        $serviceManager->get('ModuleManager')->loadModules();

        // Prepare list of listeners to bootstrap
        $listenersFromAppConfig     = isset($configuration['listeners']) ? $configuration['listeners'] : [];
        $config                     = $serviceManager->get('config');
        $listenersFromConfigService = isset($config['listeners']) ? $config['listeners'] : [];

        $listeners = array_unique(array_merge($listenersFromConfigService, $listenersFromAppConfig));

        $serviceManager->get('Application')->bootstrap($listeners)->run();
    }



    private static function inMaintenance()
    {
        if (!AppConfig::get('maintenance', 'modeMaintenance', false)) return false;

        $whiteList = AppConfig::get('maintenance', 'whiteList', []);

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
        if (self::inMaintenance()) {
            self::$maintenanceText = AppConfig::get('maintenance', 'messageInfo');
            if (php_sapi_name() !== 'cli') {
                require 'maintenance.php';
            }
        } else {
            self::zendApplicationStart();
        }
    }



    public static function error($exception)
    {
        header("HTTP/1.0 500 Internal Server Error");
        self::$maintenanceText = '<h2>Une erreur est survenue !</h2>' . $exception->getMessage();
        if (php_sapi_name() !== 'cli') {
            require 'maintenance.php';
        } else {
            echo self::$maintenanceText;
        }
    }
}





Application::init();
Application::run();