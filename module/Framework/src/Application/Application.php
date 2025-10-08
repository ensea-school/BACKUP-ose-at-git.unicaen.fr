<?php

namespace Framework\Application;

use Exception;
use Framework\Container\Container;
use Laminas\Mvc\Service\ServiceManagerConfig;
use Laminas\Stdlib\ArrayUtils;
use Locale;
use Psr\Container\ContainerInterface;

class Application
{
    private static ?self $instance = null;

    private ContainerInterface $container;

    private array $config;

    private Session $session;



    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }



    private function __construct()
    {
        // pas de construct public
    }



    private function __clone()
    {
        // Empêcher le clonage
    }



    /**
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize Application");
    }



    public function container(): ContainerInterface
    {
        return $this->container;
    }



    public function session(): Session
    {
        return $this->session;
    }



    public function config(): array
    {
        return $this->config;
    }



    public function env(): string
    {
        $forcedEnv = $this->config['dev']['forced-env'] ?? false;

        if (false !== $forcedEnv) return $forcedEnv;

        return getenv('APPLICATION_ENV') ?: 'dev';
    }



    public function inCli(): bool
    {
        return PHP_SAPI == 'cli';
    }



    public function inDev(): bool
    {
        return $this->env() == 'dev';
    }



    public function inMaintenance(): bool
    {
        if ($this->inCli()) {
            // pas de mode de maintenance en mode console
            return false;
        }

        if (!($this->config()['maintenance']['modeMaintenance'] ?? false)) {
            return false;
        }

        $whiteList = $this->config()['maintenance']['whiteList'] ?? [];

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



    public function version(): string
    {
        if (file_exists('VERSION')) {
            return file_get_contents('VERSION');
        } else {
            return 'inconnue';
        }
    }



    public function init(): void
    {
        ini_set('session.cookie_samesite', 'Strict');
        ini_set('precision', 15);

        if ($this->inCli()) { // plus de limite de RAM en CLI
            ini_set('memory_limit', '-1');
        }

        if (!defined('REQUEST_MICROTIME')) {
            define('REQUEST_MICROTIME', microtime(true));
        }

        if (!file_exists('config.local.php')) {
            die('Le fichier de configuration config.local.php doit être mis en place et configuré, or il n\'a pas été trouvé.');
        }

        $this->config = require 'config.local.php';

        Locale::setDefault($this->config['global']['locale'] ?? 'fr_FR');

        if ($this->inMaintenance()) {
            require 'public/maintenance.php';
        }

        if (!is_dir(getcwd().'/cache/Doctrine')){
            mkdir(getcwd().'/cache/Doctrine', 0777, true);
        }

        /* Définition de la config globale, éventuellement à partir du fichier de config général */
        if ($this->config()['global']['affichageErreurs'] ?? true) {
            error_reporting(E_ALL);
        } else {
            error_reporting(E_ERROR);
            set_exception_handler(function ($e) { // on affiche quand même les erreurs fatales pour expliquer!
                $this->config['maintenance']['messageInfo'] = $e;
                require 'public/maintenance.php';
            });
        }

        // Retrieve configuration
        $appConfig = require 'config/application.config.php';

        if ($this->inDev()) {
            /** @var array $devConfig */
            $devConfig = require 'config/development.config.php';
            $appConfig = ArrayUtils::merge($appConfig, $devConfig);
        }

        // Prepare the service manager
        $smConfig = new ServiceManagerConfig($appConfig['service_manager'] ?? []);

        $this->container = new Container();
        $smConfig->configureServiceManager($this->container);
        $this->container->setService('ApplicationConfig', $appConfig);

        // Crée et lance la session
        $this->session = new Session();
        $this->container->setService(Session::class, $this->session);

        // Load modules
        $this->container->get('ModuleManager')->loadModules();

        // Prepare list of listeners to bootstrap
        $listenersFromAppConfig     = $appConfig['listeners'] ?? [];
        $config                     = $this->container->get('config');
        $listenersFromConfigService = $config['listeners'] ?? [];

        $listeners = array_unique(array_merge($listenersFromConfigService, $listenersFromAppConfig));

        $this->container->get('Application')->bootstrap($listeners);
    }



    public function run(): void
    {
        if ($this->inCli()){
            // mode web uniquement!!
            return;
        }

        $app = $this->container->get('Application');
        $app->run();
    }



    public function run2(): void
    {
        if ($this->inCli()){
            // mode web uniquement!!
            return;
        }

        $mvc = new Mvc($this->container());
        $mvc->start();
    }
}