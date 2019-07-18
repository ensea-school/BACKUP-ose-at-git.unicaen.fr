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



    public function majUnicaenSymLinks(): bool
    {
        $oseDir = $this->getOseDir();

        $oldLibs = [];
        $od      = array_filter(glob($oseDir . 'public/vendor/unicaen/*'), 'is_dir');
        foreach ($od as $dir) {
            $oldLibs[] = basename($dir);
        }

        $newLibs = [];
        $nd      = array_filter(glob($oseDir . 'vendor/unicaen/*'), 'is_dir');
        foreach ($nd as $dir) {
            if (is_dir($dir . '/public')) {
                $newLibs[] = basename($dir);
            }
        }

        $deleteLibs = array_diff($oldLibs, $newLibs);
        $createLibs = array_diff($newLibs, $oldLibs);

        foreach ($deleteLibs as $lib) {
            $command = "rm $oseDir" . "public/vendor/unicaen/$lib";
            $this->console->print($command);
            $this->console->exec($command);
        }

        foreach ($createLibs as $lib) {
            $command = "cd $oseDir" . "public/vendor/unicaen;ln -sf ../../../vendor/unicaen/$lib/public $lib";
            $this->console->print($command);
            $this->console->exec($command);
        }

        return !(empty($deleteLibs) && empty($createLibs));
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



    public function purgerVersion(string $version): string
    {
        $version = strtolower($version);
        if (false !== ($p = strpos($version, 'alpha'))) {
            $version = substr($version, 0, $p);
        }
        if (false !== ($p = strpos($version, 'beta'))) {
            $version = substr($version, 0, $p);
        }

        return trim($version);
    }



    protected function getMigrationFilesToExecute(string $oldVersion, string $newVersion, string $prePost = 'pre'): array
    {
        $tags = $this->getTags(1);
        foreach ($tags as $i => $tag) {
            $tags[$i] = $this->purgerVersion($tag);
        }
        $tags = array_unique($tags);

        $oldIndex = array_search($this->purgerVersion($oldVersion), $tags);
        $newIndex = array_search($this->purgerVersion($newVersion), $tags);

        $scripts = [];

        if ($oldIndex !== false && $newIndex !== false && $oldIndex < $newIndex) {
            for ($i = $oldIndex + 1; $i <= $newIndex; $i++) {

                $phpMigr = $this->getOseDir() . 'admin/migration/' . $tags[$i] . '-' . $prePost . '.php';
                $sqlMigr = $this->getOseDir() . 'admin/migration/' . $tags[$i] . '-' . $prePost . '.sql';

                if (file_exists($sqlMigr)) {
                    $scripts[] = $sqlMigr;
                }

                if (file_exists($phpMigr)) {
                    $scripts[] = $phpMigr;
                }
            }

            return $scripts;
        } else {
            if ($prePost == 'pre') { // on n'avertit qu'une seule fois!
                $this->console->println('Attention : les scripts de migration automatiques n\'ont pas pu être déclenchés :', $this->console::BG_RED);
                if ($oldIndex === false) {
                    $this->console->println('La version précédente de OSE n\'a pas pu être clairement identifiée.', $this->console::BG_RED);
                }
                if ($newIndex === false) {
                    $this->console->println('La version cible de OSE n\'a pas pu être clairement identifiée.', $this->console::BG_RED);
                }
                if ($oldIndex == $newIndex) {
                    $this->console->println('La version cible est identique à celle déjà installée.', $this->console::BG_RED);
                }
                if ($oldIndex > $newIndex) {
                    $this->console->println('L\'installation d\'une version plus ancienne n\'est pas supportée par le système de mises à jours automatiques', $this->console::BG_RED);
                }
                $this->console->println("Afin d'effectuer vous-mêmes les opérations de migration, merci d'aller dans le répertoire /actions/migration de OSE et examiner puis exécuter les scripts nécessaires manuellement."
                    . " Ces scripts sont nommés selon la version à laquelle ils correspondent, suivis de -pre s'ils sont à exécuter AVANT la mise ) jour de la DDL de la base de données, et -post s'il sont à exécuter après."
                    . " Enfin, leur extension renseigne s'il s'agit de code PHP à exécuter ou bien de code SQL (à exécuter dans SQLDeveloper par exemple)."
                );
            }

            return [];
        }
    }



    protected function runMigrationPhpScript(string $filename)
    {
        $this->console->println('Exécution du script de migration ' . basename($filename), $this->console::COLOR_YELLOW);
        try {
            $oa = $this;
            $c  = $this->console;

            require_once $filename;
        } catch (\Exception $e) {
            $this->console->println($e->getMessage(), $this->console::COLOR_RED);
        }
    }



    protected function runMigrationSqlScript(string $filename)
    {
        $this->console->println('Exécution du script de migration ' . basename($filename), $this->console::COLOR_YELLOW);
        $errors = $this->getBdd()->execFile($filename);
        if (!empty($errors)) {
            $this->console->println('Des erreurs ont été rencontrées durant l\'exécution du script de migration :', $this->console::BG_RED);
            foreach ($errors as $e) {
                $this->console->println($e->getMessage(), $this->console::COLOR_RED);
            }
        }
    }



    /**
     * Lancement des scripts éventuels liés à des migrations pour des versions spécifiques
     *
     * @param string $prePost
     *
     * @return bool
     * @throws \BddAdmin\Exception\BddCompileException
     * @throws \BddAdmin\Exception\BddException
     * @throws \BddAdmin\Exception\BddIndexExistsException
     */
    public function migration(string $prePost = 'pre'): bool
    {
        $this->console->println('Exécution des scripts de ' . $prePost . '-migration', $this->console::COLOR_LIGHT_PURPLE);

        $scripts = $this->getMigrationFilesToExecute($this->oldVersion, $this->version, $prePost);
        foreach ($scripts as $script) {
            $ext = substr($script, -3);
            switch ($ext) {
                case 'php':
                    $this->runMigrationPhpScript($script);
                break;
                case 'sql':
                    $this->runMigrationSqlScript($script);
                break;
            }
        }

        if (count($scripts) > 0) {
            $this->console->println("Scripts de $prePost-migration exécutés");
        } else {
            $this->console->println("Aucun script de $prePost-migration à exécuter");
        }

        return true;
    }



    public function exec($args)
    {
        $this->console->passthru("php " . $this->getOseDir() . "/public/index.php " . $args);
    }



    public function getOseDir(): string
    {
        return dirname(dirname(__DIR__)) . '/';
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
        if (!$this->console){
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
    public function bddIsOk(): bool
    {
        $bddConf = Config::getBdd();

        $cs           = $bddConf['host'] . ':' . $bddConf['port'] . '/' . $bddConf['dbname'];
        $characterSet = 'AL32UTF8';
        $conn         = oci_pconnect($bddConf['username'], $bddConf['password'], $cs, $characterSet);
        if (!$conn) {
            return false;
        } else {
            oci_close($conn);

            return true;
        }
    }
}