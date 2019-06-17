<?php

class OseAdmin
{
    const OSE_ORIGIN = 'https://git.unicaen.fr/open-source/OSE';

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
     * OseAdmin constructor.
     *
     * @param Console $console
     */
    public function __construct(Console $console)
    {
        $this->console = $console;
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



    public function getTags(): array
    {
        if (false === $this->tags) {
            $this->tags = [];

            $ts = $this->console->exec("git ls-remote --tags --refs " . self::OSE_ORIGIN, false);
            foreach ($ts as $tag) {
                $this->tags[] = substr($tag, strpos($tag, 'refs/tags/') + 10);
            }
        }

        $minVersion = 8;
        foreach( $this->tags as $i => $tag ){
            $version = (int)substr($tag,0,strpos($tag,'.'));
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



    public function currentVersion(string $osedir): string
    {
        $vf = $this->getVersionFile($osedir);
        if (!file_exists($vf)) {
            return 'inconnue';
        }

        return file_get_contents($vf);
    }



    public function writeVersion(string $version)
    {
        $vf = $this->getVersionFile();
        file_put_contents($vf, $version);
    }



    private function getVersionFile(): string
    {
        return $this->getOseDir() . 'VERSION';
    }



    public function exec($args)
    {
        $this->console->passthru("php " . $this->getOseDir() . "/public/index.php " . $args);
    }



    public function getOseDir(): string
    {
        return dirname(dirname(__DIR__)).'/';
    }



    public function getBdd(): \BddAdmin\Bdd
    {
        if (!$this->bdd) {
            $this->bdd = $this->loadBdd();
        }

        return $this->bdd;
    }



    private function loadBdd(): \BddAdmin\Bdd
    {
        $bdd = new \BddAdmin\Bdd(Config::getBdd());

        return $bdd;
    }
}