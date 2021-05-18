<?php

use BddAdmin\Bdd;
use BddAdmin\Ddl\Ddl;


/*
act_arbre_odf ??
 */





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
    public $ddl;



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
        if (!$this->ddl) $this->init();

        return $this->ddl;
    }



    public function getTables(): array
    {
        return array_keys($this->getDdl()['table']);
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



    public function majTampon(string $tableName, int $anneeId): int
    {
        /* On prend la requête correspondante à la table et on récupère le contenu depuis Actul */
        $sql  = file_get_contents($this->oa->getOseDir() . 'admin/actul/query/' . $tableName . '.sql');
        $data = $this->actul->select($sql, ['v_annee' => $anneeId]);

        $key = $this->getTableKey($tableName);

        if (empty($data)) {
            return 0;
        }

        $this->colMatch($tableName, array_keys(array_change_key_case($data[0], CASE_UPPER)));

        foreach ($data as $i => $d) {
            /* On passe les colonnes en majuscules pour Oracle */
            $data[$i] = array_change_key_case($d, CASE_UPPER);

            /* Si une colonne servant de clé est nulle, alors on ne prend pas la ligne */
            foreach ($key as $k) {
                if (empty($data[$i][$k]) && array_key_exists($i, $data)) {
                    unset($data[$i]);
                }
            }
        }

        /* on fait le merge dans ose des data récupérées */
        $this->ose->getTable($tableName)->merge($data, $key);

        return count($data);
    }



    public function colMatch(string $tableName, array $cols)
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
