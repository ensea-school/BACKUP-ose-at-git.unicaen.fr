<?php

use Entity\Odf;
use Unicaen\BddAdmin\Bdd;
use Unicaen\BddAdmin\Ddl\Ddl;


/**
 *
 */
class ConnecteurPegase
{
    public Bdd         $ose;

    protected OseAdmin $oa;

    protected Bdd      $pegase;

    protected Ddl      $ddl;

    protected Odf      $odf;

    private array      $config = [];



    public function __construct()
    {
        $this->oa = OseAdmin::instance();
    }



    /**
     * @return Bdd
     */
    public function init(): Bdd
    {
        $this->odf    = new Odf();

        $config = OseAdmin::instance()->config()->get('pegase');
        $console = OseAdmin::instance()->console();
        $this->pegase = new Bdd($config['dre']);
        //$this->pegase->setLogger($this->oa->console());

        $this->ose = $this->oa->getBdd();

        $this->ddl = new Ddl();
        $this->ddl->loadFromDir($this->getDdlDir());
        $console->println('Fin de l\'initialisation');

        return $this->pegase;

    }



    public function getDdlDir()
    {
        return getcwd() .'/admin/pegase/ddl';
    }



    public function getDdl()
    {
        return $this->ddl;
    }



    public function getPegTables(): array
    {
        return [
            'PEG_STRUCTURE',
            'PEG_TYPE_FORMATION',
            'PEG_ETAPE',
            'PEG_ELEMENT_PEDAGOGIQUE',
            'PEG_CHEMIN_PEDAGOGIQUE',
            'PEG_VOLUME_HORAIRE',
        ];
    }



    public function getSyncTables(): array
    {
        $sql = "SELECT * FROM import_tables WHERE SYNC_ENABLED = 1 ORDER BY ORDRE";
        $it  = $this->ose->select($sql);
        $st  = [];
        foreach ($it as $tbl) {
            $st[] = $tbl['TABLE_NAME'];
        }

        return $st;
    }



    public function read()
    {   $c = OseAdmin::instance()->console();
        $readers    = $this->getReaders();
        $versionDre = $this->getDreVersion();
        foreach ($readers as $reader) {
            if ($reader->versionMin() <= $versionDre && $reader->versionMax() >= $versionDre) {
                $reader->run($this->pegase, $this->odf);
            }
        }

    }



    private function getReaders(): array
    {

        $readers = [];

        if (empty($this->config)) {
            $configFile = getcwd() . '/admin/pegase/config/pegase.global.php';
            if (file_exists($configFile)) {
                $this->config = require($configFile);
            }
        }
        if (isset($this->config['Readers'])) {
            foreach ($this->config['Readers'] as $reader) {
                if (class_exists($reader)) {
                    $readers[] = new $reader();
                }
            }
        }

        return $readers;
    }



    private function getDreVersion(): float
    {
        //TODO recuperer l'info en BDD DRE une fois disponible
        return 24.0;
    }



    public function adapt()
    {
        $adapters   = $this->getAdapters();
        $versionDre = $this->getDreVersion();
        foreach ($adapters as $adapter) {
            if ($adapter->versionMin() <= $versionDre && $adapter->versionMax() >= $versionDre) {
                $adapter->run($this->odf, $this->pegase);
            }
        }
    }



    private function getAdapters(): array
    {

        $adapters = [];

        if (empty($this->config)) {
            $configFile = getcwd() . 'admin/pegase/config/pegase.global.php';
            if (file_exists($configFile)) {
                $this->config = require($configFile);
            }
        }
        if (isset($this->config['Adapters'])) {
            foreach ($this->config['Adapters'] as $adapter) {
                if (class_exists($adapter)) {
                    $adapters[] = new $adapter();
                }
            }
        }

        return $adapters;
    }



    public function extractOdf()
    {
        $odfExtractors = $this->getExtractorOdf();
        $versionDre    = $this->getDreVersion();
        foreach ($odfExtractors as $odfExtractor) {
            if ($odfExtractor->versionMin() <= $versionDre && $odfExtractor->versionMax() >= $versionDre) {
                $odfExtractor->run($this->ose, $this->odf);
            }
        }
    }



    private function getExtractorOdf(): array
    {

        $extractorOdf = [];

        if (empty($this->config)) {
            $configFile = getcwd() . 'admin/pegase/config/pegase.global.php';
            if (file_exists($configFile)) {
                $this->config = require($configFile);
            }
        }
        if (isset($this->config['OdfExtractors'])) {
            foreach ($this->config['OdfExtractors'] as $extractor) {
                if (class_exists($extractor)) {
                    $extractorOdf[] = new $extractor();
                }
            }
        }

        return $extractorOdf;
    }



    public function syncTable(string $tableName)
    {
        $this->ose->exec('BEGIN UNICAEN_IMPORT.SYNCHRONISATION(:table); END;', ['table' => $tableName]);
    }



    protected function getTableKey(string $tableName): array
    {
        foreach ($this->ddl['primary-constraint'] as $key) {
            if ($key['table'] == $tableName) {
                return $key['columns'];
            }
        }

        return [];
    }




}
