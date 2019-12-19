<?php





class OseAdmin
{
    const OSE_ORIGIN  = 'https://git.unicaen.fr/open-source/OSE';
    const MIN_VERSION = 8; // version minimum installable

    /**
     * @var Console
     */
    protected $console;

    /**
     * @var Bdd
     */
    protected $bdd;

    /**
     * @var array
     */
    private $tags = false;

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



    /**
     * OseAdmin constructor.
     *
     * @param Console $console
     */
    public function __construct(Console $console = null)
    {
        if ($console) {
            $this->console = $console;
        }
    }



    public function init()
    {
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
        $gitCheck = $this->console->exec("git ls-remote --heads " . self::OSE_ORIGIN, false);

        return (false !== strpos(implode(' ', $gitCheck), 'heads/master'));
    }



    public function getTags($minVersion = self::MIN_VERSION): array
    {
        if (false === $this->tags) {
            $this->tags = [];

            $ts = $this->console->exec("git ls-remote --tags --refs " . self::OSE_ORIGIN, false);
            foreach ($ts as $tag) {
                $this->tags[] = substr($tag, strpos($tag, 'refs/tags/') + 10);
            }
        }

        foreach ($this->tags as $i => $tag) {
            $version = (int)substr($tag, 0, strpos($tag, '.'));
            if ($version < $minVersion) unset($this->tags[$i]);
        }

        return $this->tags;
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
        if (file_exists($this->getOseDir() . 'admin/actions/' . $action . '.php')) {
            if ($newProcess) {
                $this->console->passthru(
                    "php " . $this->getOseDir() . "/bin/ose " . $action
                    . ' --oa-old-version=' . $this->oldVersion
                    . ' --oa-version=' . $this->version
                );
            } else {
                $oa = $this;
                $c  = $this->console;
                require_once $this->getOseDir() . 'admin/actions/' . $action . '.php';
            }
        } else {
            $this->console->println('Action "' . $action . '" inconnue.', $this->console::COLOR_RED);
            $c = $this->console;
            require_once $this->getOseDir() . 'admin/actions/help.php';
        }
    }



    protected function runMigrationAction(string $contexte, string $action)
    {
        $file = $this->getMigrationDir() . $action . '.php';
        require_once $file;

        /**
         * @var $migration AbstractMigration
         */
        $migration = new $action($this);

        if ($contexte == $migration->getContexte() && $migration->utile()) {
            $this->console->print('[MIGRATION] ' . $migration->description() . ' ... ');

            try {
                $migration->action();
                $this->console->println('OK', $this->console::COLOR_GREEN);
            } catch (\Throwable $e) {
                $this->console->println('Erreur : ' . $e->getMessage(), $this->console::COLOR_RED);
            }
        }
    }



    public function migration(string $context = 'pre', string $action = null)
    {
        if (!is_dir($this->getMigrationDir())) return;
        $files = scandir($this->getMigrationDir());

        foreach ($files as $i => $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $fileAction = substr($file, 0, -4); // on supprime l'extension PHP
            if ($action === null || $fileAction === $action) {
                $this->runMigrationAction($context, $fileAction);
            }
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



    public function getMigrationDir()
    {
        return $this->getOseDir() . 'admin/migration/';
    }



    public function getOseAppliId(): int
    {
        if (!$this->oseAppliId) {
            $u = $this->getBdd()->select("SELECT id FROM UTILISATEUR WHERE USERNAME='oseappli'");
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
            $src = $this->getBdd()->select("SELECT id FROM SOURCE WHERE CODE='OSE'");
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
            $this->bdd = new \BddAdmin\Bdd(Config::getBdd());
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
        if (!$this->console) {
            $this->console = new Console();
        }

        return $this->console;
    }



    /**
     * @param Console $console
     *
     * @return OseAdmin
     */
    public function setConsole(Console $console): OseAdmin
    {
        $this->console = $console;

        return $this;
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