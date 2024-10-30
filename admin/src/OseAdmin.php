<?php

use Unicaen\BddAdmin\Bdd;
use Psr\Container\ContainerInterface;
use Laminas\Cli\ApplicationFactory;
use Laminas\Cli\ApplicationProvisioner;
use Laminas\Cli\ContainerResolver;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class OseAdmin
{
    private static ?OseAdmin $instance = null;

    protected ?OseConsole $console = null;

    protected ?OseConfig $config = null;

    protected ?OseEnv $env = null;

    protected ?OseRepo $repo = null;

    private ?ContainerInterface $container = null;

    protected ?Bdd $bdd = null;

    private ?int $oseAppliId = null;

    private ?int $sourceOseId = null;

    private string $maintenanceText = "OSE est actuellement en maintenance. Veuillez nous excuser pour ce désagrément.";



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

        // Haute précision float nécessaire pour les calculs d'heures comp avec les formules
        ini_set('precision', 15);

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
            $filename   = $cible . '/' . $action . '/actions/' . $sousAction . '.php';
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
                $c  = $this->console();
                require_once $filename;
            }
        } else {
            // lancement d'une commande Symphony
            $app                      = (new ApplicationFactory())();
            $definition               = $app->getDefinition();
            $output                   = new ConsoleOutput();
            $containerNotFoundMessage = '';
            $input                    = new ArgvInput();

            try {
                $input->bind($definition);
            } catch (\Symfony\Component\Console\Exception\RuntimeException $exception) {
                // Ignore validation issues as we did not yet have the commands definition
                // As we only need the `--container` option, we are good to go until it is passed *before* the first command argument
                // Symfony parses the `argv` in its direct order and raises an error when more arguments or options are passed
                // than described by the default definition.
            }

            try {
                $container = (new ContainerResolver(getcwd()))->resolve($input);
                $app       = (new ApplicationProvisioner())($app, $container);
            } catch (RuntimeException|InvalidArgumentException $exception) {
                // Usage information provided by the `ContainerResolver` should be passed to the CLI output
                $containerNotFoundMessage = sprintf('<error>%s</error>', $exception->getMessage());
            }

            // By running the app even if its not provisioned allows symfony/console to report problems
            // and/or display available options (like `--container`)
            $exitCode = $app->run(null, $output);

            if ($containerNotFoundMessage) {
                $output->writeln($containerNotFoundMessage);
                $exitCode = 255;
            }

            exit($exitCode);
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



    public function start(): void
    {
        if (!$this->container) {
            $configuration = $this->config()->getApplicationConfig();

            //Laminas\Mvc\Application::init(AppConfig::getGlobal())->run();

            // Prepare the service manager
            $smConfig = isset($configuration['service_manager']) ? $configuration['service_manager'] : [];
            $smConfig = new \Laminas\Mvc\Service\ServiceManagerConfig($smConfig);

            $serviceManager  = new Laminas\ServiceManager\ServiceManager();
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
        $c  = $this->console();
        require_once($cible);
    }



    public function getOseAppliId(): int
    {
        return $this->getBdd()->getHistoUserId();
    }



    public function getSourceOseId(): int
    {
        return $this->getBdd()->getSourceId();
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
        return $this->container()->get(Bdd::class);
//            $du->setConfig(require getcwd() . '/data/data_updater_config.php');
//            $du->addSource(new \DataSource($this));
//            $du->addSource(getcwd() . '/data/nomenclatures.php');
//            $du->addSource(getcwd() . '/data/donnees_par_defaut.php');
//            $du->addAction('privileges', 'Mise à jour des privilèges dans la base de données');
    }

}