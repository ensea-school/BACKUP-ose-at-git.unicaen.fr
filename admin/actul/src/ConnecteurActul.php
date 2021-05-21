<?php

use BddAdmin\Bdd;
use BddAdmin\Ddl\Ddl;


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
    protected $ose;

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
        $this->oa = OseAdmin::getInstance();
    }



    public function getDdlDir()
    {
        return $this->oa->getOseDir() . 'admin/actul/ddl';
    }



    public function init()
    {
        $this->actul = new Bdd(Config::get('actul'));
        $this->actul->setLogger($this->oa->getConsole());

        $this->ose = $this->oa->getBdd();

        $this->ddl = new Ddl();
        $this->ddl->loadFromDir($this->getDdlDir());
    }



    public function getDdl()
    {
        return $this->ddl;
    }



    public function sync()
    {
        $this->initDataFromActul();
        $this->makeOdf();

        $this->majActTable('ACT_ETAPE', $this->etapes);
    }



    protected function initDataFromActul()
    {
        $this->etapes = [];
        $this->odf    = [];

        /* Récupération des étapes */
        $sql = $this->getActulQuery('ACT_ETAPE');
        $ds  = $this->actul->select($sql);
        foreach ($ds as $d) {
            $this->etapes[$d['source_code']] = $d;
        }
        unset($ds);

        /* Récupération des données d'offre de formation */
        $sql = $this->getActulQuery('ACT_ODF');
        $ds  = $this->actul->select($sql);
        foreach ($ds as $d) {
            $this->odf[$d['source_code']] = $d;
        }
        unset($ds);
    }



    protected function makeOdf()
    {
        /* Construction des éléments pédagogiques */
        $this->elements = [];
        foreach ($this->odf as $i => $e) {
            if (empty($this->getChildren($i))) {
                $this->elements[] = [
                    'code' =>
                ];
            }
        }

        var_dump($this->odf);
    }



    protected function odfToElement($id): array
    {
        $element = [];
    }



    protected function getChildren($id): array
    {
        $res = [];
        foreach ($this->odf as $i => $e) {
            if ($e['element_parent_id'] == $id) {
                $res[$i] = $e;
            }
        }

        return $res;
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
        return file_get_contents($this->oa->getOseDir() . 'admin/actul/query/' . $name . '.sql');
    }



    protected function majActTable(string $tableName, array $data): int
    {
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
