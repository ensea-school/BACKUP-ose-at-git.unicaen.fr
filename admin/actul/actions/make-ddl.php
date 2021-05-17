<?php

$ca = new ConnecteurActul($oa);
$ca->init();

$table = 'ACT_DIPLOME';
$ca->majTampon($table, 2022);





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
     * @var \BddAdmin\Bdd
     */
    protected $actul;

    /**
     * @var \BddAdmin\Bdd
     */
    protected $ose;

    public    $tablesList = [
        'ACT_DIPLOME',
        'ACT_ELEMENT_COMMUN',
        'ACT_ELEMENT_EFFECTIFS',
        'ACT_ETAPE',
        'ACT_ODF_RELATIONS',
        'ACT_RESP_DIPLOME',
        'ACT_RESP_ETP',
        'ACT_RESP_VDI',
        'ACT_RESP_VET',
        'ACT_VDI_VET',
        'ACT_VET_EFFECTIFS',
        'ACT_VOLUME_HORAIRE_ENS',
    ];

    public    $ddl        = [
        //ACT_CHEMIN_PEDAGOGIQUE  =>  ose      act_chemin_pedagogique
        //ACT_ELEMENT_PEDAGOGIQUE =>  ose      act_element_pedagogique
        //ACT_ETAPE_EFFECTIFS     =>  ose      act_etape_effectifs
        //ACT_LCC_APO             =>  ose+app  act_lcc_apo
        //ACT_OFFRE_DE_FORMATION  =>  ose      act_offre_de_formation
    ];



    public function __construct(OseAdmin $oa)
    {
        $this->oa = $oa;
    }



    public function init()
    {
        $this->actul = new \BddAdmin\Bdd(Config::get('actul'));
        $this->actul->setLogger($this->oa->getConsole());

        $this->ose = $this->oa->getBdd();

        /* Récupération des infos de DDL nécessaires */
        $filter = [
            'explicit'           => true,
            'table'              => ['includes' => $this->tablesList],
            'primary-constraint' => ['includes' => '%'],
        ];

        $ddl = $this->ose->getDdl($filter);

        $this->ddl = $ddl['table'];

        foreach ($ddl['primary-constraint'] as $n => $d) {
            if (isset($this->ddl[$d['table']])) {
                $this->ddl[$d['table']]['key'] = $d['columns'];
            }
        }
    }



    public function majTampon(string $tableName, int $anneeId)
    {
        /* On prend la requête correspondante à la table et on récupère le contenu depuis Actul */
        $sql  = file_get_contents($this->oa->getOseDir() . 'admin/actul/query/' . $tableName . '.sql');
        $data = $this->actul->select($sql, ['v_annee' => $anneeId]);

        $table = $this->ose->getTable($tableName);
        $ddl   = $table->getDdl();
        var_dump($ddl);
        $key = $this->tables[$tableName]['key'];
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
    }
}
