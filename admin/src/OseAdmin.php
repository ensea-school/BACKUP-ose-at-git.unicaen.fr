<?php





class OseAdmin
{
    const OSE_ORIGIN  = 'https://git.unicaen.fr/open-source/OSE.git';
    const MIN_VERSION = 14; // version minimum installable

    private static ?OseAdmin $instance = null;

    protected Console        $console;

    protected ?\BddAdmin\Bdd $bdd      = null;

    /**
     * @var array
     */
    private $tags = false;

    /**
     * @var array
     */
    private $branches = false;

    /**
     * @var int
     */
    private $oseAppliId;

    /**
     * @var int
     */
    private $sourceOseId;

    /**
     * @var string
     */
    public $oldVersion;

    /**
     * @var string
     */
    public $version;



    private function __construct()
    {
    }



    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
            self::$instance->init();
        }

        return self::$instance;
    }



    public function init()
    {
        spl_autoload_register(function ($class) {
            $root = self::getInstance()->getOseDir();

            $dirs = [
                $root . '/admin/src/',
                $root . '/admin/actul/src/',
            ];

            foreach ($dirs as $dir) {
                $filename = $dir . str_replace('\\', '/', $class) . '.php';

                if (file_exists($filename)) {
                    require_once $filename;
                    break;
                }
            }
        });

        $this->console = new Console();

        $this->version    = $this->currentVersion();
        $this->oldVersion = $this->version;

        if ($this->console->hasOption('oa-old-version')) {
            $this->oldVersion = $this->console->getOption('oa-old-version');
        }
        if ($this->console->hasOption('oa-version')) {
            $this->version = $this->console->getOption('oa-version');
        }
    }



    public function gitlabIsReachable(): bool
    {
        return $this->brancheIsValid('master');
    }



    public function getTags($minVersion = self::MIN_VERSION): array
    {
        if (false === $this->tags) {
            $this->tags = [];

            $ts = $this->console->exec("git ls-remote --tags --refs " . self::OSE_ORIGIN, false);
            foreach ($ts as $tag) {
                $this->tags[] = substr($tag, strpos($tag, 'refs/tags/') + 10);
            }

            usort($this->tags, function ($a, $b) {
                if ((string)(int)$a !== $a) {
                    $va = (int)substr($a, 0, strpos($a, '.'));
                } else {
                    $va = (int)$a;
                }
                if ((string)(int)$b !== $b) {
                    $vb = (int)substr($b, 0, strpos($b, '.'));
                } else {
                    $vb = (int)$b;
                }

                if ($va == $vb) return 1;

                return $va - $vb;
            });
        }

        $tags = $this->tags;
        foreach ($tags as $i => $tag) {
            if ((string)(int)$tag !== $tag) {
                $version = (int)substr($tag, 0, strpos($tag, '.'));
            } else {
                $version = (int)$tag;
            }
            if ($version < $minVersion) unset($tags[$i]);
        }

        return $tags;
    }



    public function getBranches(): array
    {
        if (false === $this->branches) {
            $this->branches = [];

            $bs = $this->console->exec("git ls-remote --heads --refs " . self::OSE_ORIGIN, false);
            foreach ($bs as $branche) {
                $this->branches[] = substr($branche, strpos($branche, 'refs/heads/') + 11);
            }

            sort($this->branches);
        }

        return $this->branches;
    }



    public function getCurrentBranche(): ?string
    {
        $ts = $this->console->exec("git branch", false);
        foreach ($ts as $t) {
            if (0 === strpos($t, '*')) {
                return trim(substr($t, 1));
            }
        }

        return null;
    }



    /**
     * @param string $tag
     *
     * @return bool
     */
    public function tagIsValid(string $tag): bool
    {
        return in_array($tag, $this->getTags());
    }



    /**
     * @param string $tag
     *
     * @return bool
     */
    public function brancheIsValid(string $branche): bool
    {
        return in_array($branche, $this->getBranches());
    }



    public function currentVersion(): string
    {
        $vf = $this->getOseDir() . 'VERSION';
        if (!file_exists($vf)) {
            return 'inconnue';
        }

        return trim(file_get_contents($vf));
    }



    public function writeVersion(string $version)
    {
        $this->version = $version;
        file_put_contents($this->getOseDir() . 'VERSION', $version);
    }



    /**
     * @param string $action
     */
    public function run(string $action, $newProcess = false)
    {
        $cible = $this->getOseDir() . 'admin/';

        if (file_exists($cible . 'actions/' . $action . '.php')) {
            $filename = $cible . 'actions/' . $action . '.php';
        } elseif (is_dir($cible . $action)) {
            $sousAction = $this->getConsole()->getArg(2);
            $filename   = $cible . $action . '/actions/' . $sousAction . '.php';
        }

        if ($filename) {
            if ($newProcess) {
                $this->console->passthru(
                    "php " . $this->getOseDir() . "/bin/ose " . $action
                    . ' --oa-old-version=' . $this->oldVersion
                    . ' --oa-version=' . $this->version
                );
            } else {
                $oa = $this;
                $c  = $this->console;
                require_once $filename;
            }
        } else {
            $this->console->println('Action "' . $action . '" inconnue.', $this->console::COLOR_RED);
            $c = $this->console;
            require_once $this->getOseDir() . 'admin/actions/help.php';
        }
    }



    public function exec($args)
    {
        $this->console->passthru("php " . $this->getOseDir() . "/public/index.php " . $args);
    }



    public function getOseDir(): string
    {
        return dirname(dirname(__DIR__)) . '/';
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



    /**
     * @return \BddAdmin\Bdd
     */
    public function getBdd(): \BddAdmin\Bdd
    {
        if (!$this->bdd) {
            if (!$this->bddIsOk($msg)) {
                $this->console->printDie("Impossible d'accéder à la base de données : $msg!"
                    . "\nVeuillez contrôler vos paramètres de configuration s'il vous plaît, avant de refaire une tentative de MAJ de la base de données (./bin/ose update-bdd).");
            }
            $this->bdd = new \BddAdmin\Bdd(Config::getBdd());
            if (PHP_SAPI == 'cli') {
                $this->bdd->setLogger($this->console);
            }
        }

        return $this->bdd;
    }



    /**
     * @param \BddAdmin\Bdd $bdd
     *
     * @return $this
     */
    public function setBdd(\BddAdmin\Bdd $bdd)
    {
        $this->bdd = $bdd;

        return $this;
    }



    /**
     * @return Console
     */
    public function getConsole(): Console
    {
        return $this->console;
    }



    /**
     * @return bool
     */
    public function bddIsOk(&$msg): bool
    {
        $bddConf = Config::getBdd();

        $cs           = $bddConf['host'] . ':' . $bddConf['port'] . '/' . $bddConf['dbname'];
        $characterSet = 'AL32UTF8';
        $conn         = @oci_pconnect($bddConf['username'], $bddConf['password'], $cs, $characterSet);
        if (!$conn) {
            $msg = oci_error()['message'];

            return false;
        } else {
            oci_close($conn);

            return true;
        }
    }
}