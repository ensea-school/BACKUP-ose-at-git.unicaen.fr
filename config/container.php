<?php

use Laminas\Mvc\Service\ServiceManagerConfig;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Stdlib\ArrayUtils;
use Psr\Container\ContainerInterface;

class AppAdmin
{
    private static ContainerInterface $container;
    private static array              $config;



    public static function container(): ContainerInterface
    {
        return self::$container;
    }



    public static function config(): array
    {
        return self::$config;
    }



    public static function env(): string
    {
        $forcedEnv = self::$config['dev']['forced-env'] ?? false;

        if (false !== $forcedEnv) return $forcedEnv;

        return getenv('APPLICATION_ENV') ?: 'dev';
    }



    public static function inDev(): bool
    {
        return self::env() == 'dev';
    }



    public function inMaintenance(): bool
    {
        if (self::inCli()) {
            // pas de mode de maintenance en mode console
            return false;
        }

        if (!(self::config()['maintenance']['modeMaintenance'] ?? false)) {
            return false;
        }

        $whiteList = self::config()['maintenance']['whiteList'] ?? [];

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



    public static function inCli(): bool
    {
        return PHP_SAPI == 'cli';
    }



    public static function version(): string
    {
        if (file_exists('VERSION')) {
            return file_get_contents('VERSION');
        } else {
            return 'inconnue';
        }
    }



    public static function init(): ContainerInterface
    {
        if (!file_exists('config.local.php')) {
            die('Le fichier de configuration config.local.php doit être mis en place et configuré, or il n\'a pas été trouvé.');
        }

        self::$config = require 'config.local.php';

        // Retrieve configuration
        $appConfig = require __DIR__ . '/application.config.php';
        if (self::inDev()) {
            /** @var array $devConfig */
            $devConfig = require __DIR__ . '/development.config.php';
            $appConfig = ArrayUtils::merge($appConfig, $devConfig);
        }

//        $application = Application::init($appConfig);

        // Prepare the service manager
        $smConfig = new ServiceManagerConfig($appConfig['service_manager'] ?? []);

        self::$container = new ServiceManager();
        $smConfig->configureServiceManager(self::$container);
        self::$container->setService('ApplicationConfig', $appConfig);

        // Load modules
        self::$container->get('ModuleManager')->loadModules();

        // Prepare list of listeners to bootstrap
        $listenersFromAppConfig     = $appConfig['listeners'] ?? [];
        $config                     = self::$container->get('config');
        $listenersFromConfigService = $config['listeners'] ?? [];

        $listeners = array_unique(array_merge($listenersFromConfigService, $listenersFromAppConfig));

        $application = self::$container->get('Application')->bootstrap($listeners);

        return self::$container;
    }
}

return AppAdmin::init();