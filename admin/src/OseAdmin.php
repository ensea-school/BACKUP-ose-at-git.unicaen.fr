<?php

use Unicaen\BddAdmin\Bdd;
use Unicaen\BddAdmin\DataUpdater;
use Psr\Container\ContainerInterface;

class OseAdmin
{
    private static ?OseAdmin $instance = null;

    protected ?OseConsole $console = null;

    protected ?OseConfig $config = null;

    protected ?OseEnv $env = null;

    protected ?OseRepo $repo = null;

    private ?ContainerInterface $container = null;

    protected ?Bdd $bdd = null;

    protected ?DataUpdater $dataUpdater = null;

    private ?int $oseAppliId = null;

    private ?int $sourceOseId = null;

    private string $maintenanceText = "OSE est actuellement en maintenance. Veuillez nous excuser pour ce déagrément.";



    public static function instance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
            self::$instance->init();
        }

        return self::$instance;
    }



    public function console(): OseConsole
    {
        if (!$this->console) {
            $this->console = new OseConsole();
        }

        return $this->console;
    }



    public function config(): OseConfig
    {
        if (!$this->config) {
            $this->config = new OseConfig();
        }

        return $this->config;
    }



    public function env(): OseEnv
    {
        if (!$this->env) {
            $this->env = new OseEnv($this);
        }

        return $this->env;
    }



    public function repo(): OseRepo
    {
        if (!$this->repo) {
            $this->repo = new OseRepo($this);
        }

        return $this->repo;
    }



    private function init()
    {
        if (!defined('REQUEST_MICROTIME')) {
            define('REQUEST_MICROTIME', microtime(true));
        }

        /* Définition de la config globale, éventuellement à partir du fichier de config général */
        if ($this->config()->get('global', 'affichageErreurs')) {
            error_reporting(E_ALL);
        } else {
            error_reporting(E_ERROR);
            set_exception_handler(function ($e) { // on affiche quand même les erreurs fatales pour expliquer!
                $this->webAppError($e);
            });
        }

        \Locale::setDefault($this->config()->get('global', 'locale'));

        if (!class_exists('Laminas\Loader\AutoloaderFactory')) {
            throw new RuntimeException('Impossible de démarrer Laminas. Exécutez `php composer.phar install`');
        }
    }



    public function run(string $action, $newProcess = false): void
    {
        $cible = getcwd() . '/admin';

        if (file_exists($cible . '/actions/' . $action . '.php')) {
            $filename = $cible . '/actions/' . $action . '.php';
        } elseif (is_dir($cible . '/' . $action)) {
            $sousAction = $this->console()->getArg(2);
            $filename = $cible . '/' . $action . '/actions/' . $sousAction . '.php';
        } else {
            $filename = null;
        }

        if ($filename) {
            if ($newProcess) {
                $this->console()->passthru(
                    "php " . getcwd() . "/bin/ose " . $action
                );
            } else {
                $oa = $this;
                $c = $this->console();
                require_once $filename;
            }
        } else {
            $this->console()->println('Action "' . $action . '" inconnue.', $this->console()::COLOR_RED);
            $c = $this->console();
            require_once getcwd() . '/admin/actions/help.php';
        }
    }



    public function runWebApp(): void
    {
        if (php_sapi_name() !== 'cli' && $this->inMaintenance()) {
            $this->maintenanceText = $this->config()->get('maintenance', 'messageInfo');
            require 'public/maintenance.php';
        } else {
            ini_set('session.cookie_samesite', 'Strict');

            $this->container()->get('Application')->run();
        }
    }



    private function webAppError($exception)
    {
        header("HTTP/1.0 500 Internal Server Error");
        $this->maintenanceText = '<h2>Une erreur est survenue !</h2>'
            . '<p>' . $exception->getMessage() . '</p>'
            . '<p style="color:darkred">' . $exception->getFile() . ' ligne ' . $exception->getLine() . '</p>';
        if (!$this->env()->inConsole()) {
            require 'public/maintenance.php';
        } else {
            echo $this->maintenanceText . "\n";
        }
        die();
    }



    private function start(): void
    {
        if (!$this->container) {
            $configuration = $this->config()->getApplicationConfig();

            //Laminas\Mvc\Application::init(AppConfig::getGlobal())->run();

            // Prepare the service manager
            $smConfig = isset($configuration['service_manager']) ? $configuration['service_manager'] : [];
            $smConfig = new \Laminas\Mvc\Service\ServiceManagerConfig($smConfig);

            $serviceManager = new Laminas\ServiceManager\ServiceManager();
            $this->container = $serviceManager;
            $smConfig->configureServiceManager($serviceManager);
            $serviceManager->setService('ApplicationConfig', $configuration);

            // Load modules
            /** @var $moduleManager \Laminas\ModuleManager\ModuleManager */
            $moduleManager = $serviceManager->get('ModuleManager');
            $moduleManager->loadModules();

            $serviceManager->get('Application')->bootstrap([]);
        }
    }



    public function container(): ContainerInterface
    {
        if (!$this->container) {
            $this->start();
        }

        return $this->container;
    }



    public function getController(string $name): object
    {
        return $this->container()->get('ControllerManager')->get($name);
    }



    public function test(string $action): void
    {
        $cible = getcwd() . '/tests/' . $action . '.php';

        if (!file_exists($cible)) {
            $this->console()->printDie("Le fichier $cible n'existe pas");
        }

        $oa = $this;
        $c = $this->console();
        require_once($cible);
    }



    public function getOseAppliId(): int
    {
        if (!$this->oseAppliId) {
            $u = $this->getBdd()->select("SELECT ID FROM UTILISATEUR WHERE USERNAME='oseappli'");
            if (isset($u[0]['ID'])) {
                $this->oseAppliId = (int)$u[0]['ID'];
            } else {
                throw new \Exception('Utilisateur système "oseappli" non trouvé!!');
            }
        }

        return $this->oseAppliId;
    }



    public function getSourceOseId(): int
    {
        if (!$this->sourceOseId) {
            $src = $this->getBdd()->select("SELECT ID FROM SOURCE WHERE CODE='OSE'");
            if (isset($src[0]['ID'])) {
                $this->sourceOseId = (int)$src[0]['ID'];
            } else {
                throw new \Exception('Source d\'import "OSE" non trouvée!!');
            }
        }

        return $this->sourceOseId;
    }



    public function inMaintenance(): bool
    {
        if ($this->env()->inConsole()) {
            // pas de mode de maintenance en mode console
            return false;
        }

        $inMaintenance = $this->config()->get('maintenance', 'modeMaintenance', false);
        if (!$inMaintenance) {
            return false;
        }

        $whiteList = $this->config()->get('maintenance', 'whiteList', []);

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



    public function maintenanceText(): string
    {
        return $this->maintenanceText;
    }



    public function getBdd(): Bdd
    {
        if (!$this->bdd) {
            $this->bdd = new Bdd($this->config()->get('bdd'));
            if (PHP_SAPI == 'cli') {
                $this->bdd->setLogger($this->console());
            }

            try {
                $this->bdd->setOption('source-id', $this->getSourceOseId());
                $this->bdd->setOption('histo-user-id', $this->getOseAppliId());
            } catch (\Exception $e) {

            }

            $du = $this->bdd->dataUpdater();
            $du->setConfig(require getcwd() . '/data/data_updater_config.php');
            $du->addSource(new \DataSource($this));
            $du->addSource(getcwd() . '/data/nomenclatures.php');
            $du->addSource(getcwd() . '/data/donnees_par_defaut.php');
            $du->addAction('privileges', 'Mise à jour des privilèges dans la base de données');
        }

        return $this->bdd;
    }

}