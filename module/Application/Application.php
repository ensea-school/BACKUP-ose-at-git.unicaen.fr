<?php





class Application
{
    /**
     * @var Psr\Container\ContainerInterface
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
        $appDir = dirname(dirname(__DIR__));

        define('REQUEST_MICROTIME', microtime(true));
        chdir($appDir);

//        if (!file_exists($appDir.'/cache/sessions')){
//            mkdir($appDir.'/cache/sessions');
//        }
//        session_save_path ($appDir.'/cache/sessions' );

        /* Définition de la config globale, éventuellement à partir du fichier de config général */
        if (AppConfig::get('global', 'affichageErreurs')) {
            error_reporting(E_ALL);
        } else {
            set_exception_handler(function ($e) { // on affiche quand même les erreurs fatales pour expliquer!
                self::error($e);
            });
        }

        \Locale::setDefault(AppConfig::get('global', 'locale'));
        putenv("NLS_LANGUAGE=FRENCH");

        /* Chargement de l'autoloader */
        if (file_exists($appDir . '/vendor/autoload.php')) {
            include $appDir . '/vendor/autoload.php';
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

        $serviceManager  = new Zend\ServiceManager\ServiceManager();
        self::$container = $serviceManager;
        $smConfig->configureServiceManager($serviceManager);
        $serviceManager->setService('ApplicationConfig', $configuration);

        // Load modules
        /** @var $moduleManager \Zend\ModuleManager\ModuleManager */
        $moduleManager = $serviceManager->get('ModuleManager');
        $moduleManager->loadModules();
        $application = $serviceManager->get('Application')->bootstrap([]);
        /** @var $application \Zend\Mvc\Application */
        $application->run();
    }



    private static function inMaintenance()
    {
        if (!AppConfig::get('maintenance', 'modeMaintenance', false)) return false;

        $whiteList = AppConfig::get('maintenance', 'whiteList', []);

        $passed = false;
        foreach ($whiteList as $ip) {
            $passed = $ip[0] === (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null);
            if ($passed && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $passed = isset($ip[1]) && $ip[1] === $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            if ($passed) break;
        }

        return !$passed;
    }



    public static function run()
    {
        if (php_sapi_name() !== 'cli' && self::inMaintenance()) {
            self::$maintenanceText = AppConfig::get('maintenance', 'messageInfo');
            require 'public/maintenance.php';
            die();
        } else {
            self::zendApplicationStart();
        }
    }



    public static function error($exception)
    {
        header("HTTP/1.0 500 Internal Server Error");
        self::$maintenanceText = '<h2>Une erreur est survenue !</h2>' . $exception->getMessage();
        if (php_sapi_name() !== 'cli') {
            require 'public/maintenance.php';
        } else {
            echo self::$maintenanceText . "\n";
        }
        die();
    }
}