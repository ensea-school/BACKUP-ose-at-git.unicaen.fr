<?php

use Unicaen\BddAdmin\Bdd;
use Unicaen\BddAdmin\Ddl\Ddl;


class ConnecteurActul
{
    /**
     * @var OseAdmin
     */
    protected $oa;

    /**
     * @var Bdd
     */
    protected $actul;

    /**
     * @var Bdd
     */
    public $ose;

    /**
     * @var Ddl
     */
    protected $ddl;

    /**
     * @var array
     */
    protected $odf = [];

    /**
     * @var array
     */
    protected $etapes = [];

    /**
     * @var array
     */
    protected $elements = [];

    /**
     * @var array
     */
    protected $chemins = [];

    /**
     * @var array
     */
    protected $noeuds = [];

    /**
     * @var array
     */
    protected $liens = [];



    public function __construct()
    {
        $this->oa = OseAdmin::instance();
    }



    public function getDdlDir()
    {
        return getcwd() . '/admin/actul/ddl';
    }



    public function init()
    {
        $bddConf = OseAdmin::instance()->config()->get('actul');
        $this->actul = new Bdd($bddConf);
        $this->actul->setLogger($this->oa->console());

        $this->ose = $this->oa->getBdd();

        $this->ddl = new Ddl();
        $this->ddl->loadFromDir($this->getDdlDir());
    }



    public function getDdl()
    {
        return $this->ddl;
    }



    public function getActTables(): array
    {
        return [
            'ACT_ETAPE',
            'ACT_NOEUD',
            'ACT_LIEN',
            'ACT_VHENS_HEURES',
            'ACT_VHENS_GROUPES',
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



    protected function getTableKey(string $tableName): array
    {
        foreach ($this->ddl['primary-constraint'] as $key) {
            if ($key['table'] == $tableName) {
                return $key['columns'];
            }
        }

        return [];
    }



    protected function getActulQuery($name): string
    {
        return file_get_contents(getcwd() . '/admin/actul/query/' . $name . '.sql');
    }



    public function majActTable(string $tableName): int
    {
        $sql  = $this->getActulQuery($tableName);
        $data = $this->actul->select($sql, [], ['fetch' => Bdd::FETCH_ALL]);

        $key = $this->getTableKey($tableName);

        if (empty($data)) {
            return 0;
        }

        $this->colMatch($tableName, array_keys(array_change_key_case(current($data), CASE_UPPER)));

        foreach ($data as $i => $d) {
            /* On passe les colonnes en majuscules pour Oracle */
            $data[$i] = array_change_key_case($d, CASE_UPPER);
        }

        /* on fait le merge dans ose des data récupérées */
        $this->ose->getTable($tableName)->merge($data, $key);

        return count($data);
    }



    public function syncTable(string $tableName)
    {
        $this->ose->exec('BEGIN UNICAEN_IMPORT.SYNCHRONISATION(:table); END;', ['table' => $tableName]);
    }



    protected function colMatch(string $tableName, array $cols)
    {
        $dc = array_keys($this->ddl['table'][$tableName]['columns']);


        sort($dc);
        sort($cols);

        $diff = array_diff($dc, $cols);

        if (!empty($diff)) {
            // il y a un écart ==> erreur!!!
            throw new \Exception(
                "La table $tableName a un problème : les colonnes de la requête ne correspondent pas. "
                . "Ecarts : " . implode(', ', $diff)
            );
        }
    }
}
