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
        \Locale::setDefault('fr_FR');
        define('REQUEST_MICROTIME', microtime(true));
        chdir(dirname(__DIR__));

        if (file_exists('vendor/autoload.php')) {
            include 'vendor/autoload.php';
        }

        if (!class_exists('Zend\Loader\AutoloaderFactory')) {
            throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');
        }
        require 'config/application.config.php';

        if (!AppConfig::get('global', 'affichageErreurs')) {
            set_exception_handler(function ($e) { // on affiche quand même les erreurs fatales pour expliquer!
                self::error($e);
            });
        }
    }



    private static function startContainer()
    {
        self::$container = new Zend\ServiceManager\ServiceManager(new Zend\Mvc\Service\ServiceManagerConfig([]));
        self::$container->setService('ApplicationConfig', AppConfig::getGlobal());
        self::$container->get('ModuleManager')->loadModules();
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
            self::startContainer();

            if (AppConfig::get('global', 'affichageErreurs')) {
                error_reporting(E_ALL);
            }
            putenv("NLS_LANGUAGE=FRENCH");


            if (AppConfig::get('global', 'modeInstallation', true)) {
                require 'install.php';
            } else {
                self::$container->get('Application')->bootstrap([])->run();
            }
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