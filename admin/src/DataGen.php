<?php





class DataGen
{
    /**
     * @var OseAdmin
     */
    private $oseAdmin;

    /**
     * @var array
     */
    private $nomenclature = [];

    /**
     * @var array
     */
    private $donneesDefaut = [];

    private $config        = [
        /* Obligatoire au début */
        [
            'table'   => 'UTILISATEUR',
            'context' => ['install', 'update'],
            'key'     => 'USERNAME',
            'options' => ['update-ignore-cols' => ['EMAIL', 'PASSWORD'], 'delete' => false],
        ],
        [
            'table'   => 'SOURCE',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
            'options' => ['delete' => false],
        ],


        /* Nomenclatures fixes et jamais paramétrables */
        [
            'table'   => 'CIVILITE',
            'context' => ['install', 'update'],
            'key'     => ['LIBELLE_COURT'],
        ],
        [
            'table'   => 'PLAFOND',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
        ],
        [
            'table'   => 'PLAFOND_ETAT',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
        ],
        [
            'table'   => 'TYPE_VOLUME_HORAIRE',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
        ],
        [
            'table'   => 'ETAT_VOLUME_HORAIRE',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
        ],
        [
            'table'   => 'PERIMETRE',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
        ],
        [
            'table'   => 'TYPE_VALIDATION',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
        ],
        [
            'table'   => 'TBL',
            'context' => ['install', 'update'],
            'key'     => 'TBL_NAME',
        ],
        [
            'table'   => 'WF_ETAPE',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
            'options' => ['update-ignore-cols' => ['LIBELLE_INTERVENANT', 'LIBELLE_AUTRES']],
        ],
        [
            'table'   => 'TYPE_AGREMENT',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
        ],
        [
            'table'   => 'TYPE_CONTRAT',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
        ],
        [
            'table'   => 'CATEGORIE_PRIVILEGE',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
        ],
        [
            'table'   => 'PRIVILEGE',
            'context' => ['install', 'update'],
            'key'     => ['CATEGORIE_ID', 'CODE'],
            'options' => ['columns' => ['CATEGORIE_ID' => ['transformer' => 'SELECT id FROM categorie_privilege WHERE code = %s']]],
        ],
        [
            'table'   => 'INDICATEUR',
            'context' => ['install', 'update'],
            'key'     => 'NUMERO',
        ],
        [
            'table'   => 'FORMULE',
            'context' => ['install', 'update'],
        ],
        [
            'table'   => 'FORMULE_TEST_STRUCTURE',
            'context' => ['install', 'update'],
        ],
        [
            'table'   => 'TYPE_HEURES',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
            'options' => ['update-ignore-cols' => ['ID', 'TYPE_HEURES_ELEMENT_ID']],
        ],
        [
            'table'   => 'TYPE_INTERVENANT',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
        ],
        [
            'table'   => 'PERIODE',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
            'options' => ['delete' => false],
        ],
        [
            'table'   => 'TAUX_HORAIRE_HETD',
            'context' => ['install', 'update'],
        ],
        [
            'table'   => 'TYPE_RESSOURCE',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
            'options' => ['update' => false, 'delete' => false],
        ],


        /* Nomenclatures partiellement paramétrables (certaines colonnes) */
        [
            'table'   => 'ANNEE',
            'context' => ['install', 'update'],
            'key'     => 'ID',
            'options' => ['update-ignore-cols' => ['ACTIVE', 'TAUX_HETD']],
        ],
        [
            'table'   => 'REGLE_STRUCTURE_VALIDATION',
            'context' => ['install', 'update'],
            'key'     => ['TYPE_VOLUME_HORAIRE_ID', 'TYPE_INTERVENANT_ID'],
            'options' => ['update-ignore-cols' => ['PRIORITE']],
        ],


        /* Tables avec paramétrages pré-configurés (certaines colonnes + nouveaux enregistrements) */
        [
            'table'   => 'IMPORT_TABLES',
            'context' => ['install', 'update'],
            'key'     => 'TABLE_NAME',
            'options' => ['update' => false, 'delete' => false],
        ],
        [
            'table'   => 'CC_ACTIVITE',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
            'options' => ['update' => false, 'delete' => false],
        ],
        [
            'table'   => 'TYPE_INTERVENTION',
            'context' => ['install'],
            'key'     => 'CODE',
        ],
        [
            'table'   => 'SCENARIO',
            'context' => ['install'],
            'key'     => 'LIBELLE',
            'options' => ['delete' => false],
        ],
        [
            'table'   => 'ETAT_SORTIE',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
            'options' => ['update' => false, 'delete' => false],
        ],
        [
            'table'   => 'MODELE_CONTRAT',
            'context' => ['install'],
        ],
        [
            'table'   => 'ROLE',
            'context' => ['install'],
            'key'     => 'CODE',
            'options' => ['columns' => ['PERIMETRE_ID' => ['transformer' => 'SELECT id FROM perimetre WHERE code = %s']]],
        ],
        [
            'table'   => 'ROLE_PRIVILEGE',
            'context' => ['install'],
            'key'     => ['ROLE_ID', 'PRIVILEGE_ID'],
            'options' => ['columns' => [
                'ROLE_ID'      => ['transformer' => 'SELECT id FROM role WHERE histo_destruction IS NULL AND code = %s'],
                'PRIVILEGE_ID' => ['transformer' => 'SELECT p.id FROM privilege p JOIN categorie_privilege cp ON cp.id = p.categorie_id WHERE cp.code || \'-\' || p.code = %s'],
            ],],
        ],
        [
            'table'   => 'AFFECTATION',
            'context' => ['install'],
            'key'     => ['UTILISATEUR_ID', 'ROLE_ID'],
            'options' => ['columns' => [
                'ROLE_ID'        => ['transformer' => 'SELECT id FROM role WHERE histo_destruction IS NULL AND code = %s'],
                'UTILISATEUR_ID' => ['transformer' => 'SELECT id FROM utilisateur WHERE username = %s'],
            ],],
        ],


        /* Jeu de données de configuration par défaut (tout perso) */
        [
            'table'   => 'PAYS',
            'context' => ['install'],
            'key'     => 'SOURCE_CODE',
        ],
        [
            'table'   => 'DEPARTEMENT',
            'context' => ['install'],
            'key'     => 'SOURCE_CODE',
        ],
        [
            'table'   => 'ETABLISSEMENT',
            'context' => ['install'],
            'key'     => 'SOURCE_CODE',
        ],
        [
            'table'   => 'CORPS',
            'context' => ['install'],
            'key'     => 'SOURCE_CODE',
        ],
        [
            'table'   => 'GRADE',
            'context' => ['install'],
            'key'     => 'SOURCE_CODE',
            'options' => ['columns' => ['CORPS_ID' => ['transformer' => 'SELECT id FROM corps WHERE source_code = %s']]],
        ],
        [
            'table'   => 'DISCIPLINE',
            'context' => ['install'],
            'key'     => 'SOURCE_CODE',
        ],
        [
            'table'   => 'DOMAINE_FONCTIONNEL',
            'context' => ['install'],
            'key'     => 'SOURCE_CODE',
        ],
        [
            'table'   => 'FONCTION_REFERENTIEL',
            'context' => ['install'],
            'key'     => 'CODE',
            'options' => ['columns' => ['DOMAINE_FONCTIONNEL_ID' => ['transformer' => 'SELECT id FROM domaine_fonctionnel WHERE source_code = %s']]],
        ],
        [
            'table'   => 'MOTIF_MODIFICATION_SERVICE',
            'context' => ['install'],
            'key'     => 'CODE',
        ],
        [
            'table'   => 'MOTIF_NON_PAIEMENT',
            'context' => ['install'],
            'key'     => 'CODE',
        ],
        [
            'table'   => 'STATUT_INTERVENANT',
            'context' => ['install'],
            'key'     => 'SOURCE_CODE',
            'options' => ['columns' => ['TYPE_INTERVENANT_ID' => ['transformer' => 'SELECT id FROM TYPE_INTERVENANT WHERE code = %s']]],
        ],
        [
            'table'   => 'STATUT_PRIVILEGE',
            'context' => ['install'],
            'key'     => ['STATUT_ID', 'PRIVILEGE_ID'],
            'options' => ['columns' => [
                'STATUT_ID'    => ['transformer' => 'SELECT id FROM statut_intervenant WHERE histo_destruction IS NULL AND source_code = %s'],
                'PRIVILEGE_ID' => ['transformer' => 'SELECT p.id FROM privilege p JOIN categorie_privilege cp ON cp.id = p.categorie_id WHERE cp.code || \'-\' || p.code = %s'],
            ],],
        ],
        [
            'table'   => 'TYPE_AGREMENT_STATUT',
            'context' => ['install'],
            'key'     => ['STATUT_INTERVENANT_ID', 'TYPE_AGREMENT_ID'],
            'options' => ['columns' => [
                'STATUT_INTERVENANT_ID' => ['transformer' => 'SELECT id FROM statut_intervenant WHERE histo_destruction IS NULL AND source_code = %s'],
                'TYPE_AGREMENT_ID'      => ['transformer' => 'SELECT id FROM TYPE_AGREMENT WHERE code = %s'],
            ],],
        ],
        [
            'table'   => 'TYPE_PIECE_JOINTE',
            'context' => ['install'],
            'key'     => 'CODE',
        ],
        [
            'table'   => 'TYPE_PIECE_JOINTE_STATUT',
            'context' => ['install'],
            'key'     => ['STATUT_INTERVENANT_ID', 'TYPE_PIECE_JOINTE_ID'],
            'options' => ['columns' => [
                'STATUT_INTERVENANT_ID' => ['transformer' => 'SELECT id FROM statut_intervenant WHERE histo_destruction IS NULL AND source_code = %s'],
                'TYPE_PIECE_JOINTE_ID'  => ['transformer' => 'SELECT id FROM TYPE_PIECE_JOINTE WHERE histo_destruction IS NULL AND code = %s'],
            ],],
        ],
        [
            'table'   => 'WF_ETAPE_DEP',
            'context' => ['install'],
            'key'     => ['ETAPE_SUIV_ID', 'ETAPE_PREC_ID'],
            'options' => ['columns' => [
                'ETAPE_PREC_ID'       => ['transformer' => 'SELECT id FROM wf_etape WHERE code = %s'],
                'ETAPE_SUIV_ID'       => ['transformer' => 'SELECT id FROM wf_etape WHERE code = %s'],
                'TYPE_INTERVENANT_ID' => ['transformer' => 'SELECT id FROM type_intervenant WHERE code = %s'],
            ],],
        ],

        /* Paramètres par défaut, en fonction des nomenclatures ci-dessus */
        [
            'table'   => 'PARAMETRE',
            'context' => ['install', 'update'],
            'key'     => 'NOM',
            'options' => ['update-ignore-cols' => ['VALEUR']],
        ],
    ];



    public function __construct(OseAdmin $oseAdmin)
    {
        $this->oseAdmin = $oseAdmin;
    }



    public function install(string $table = null)
    {
        return $this->action('install', $table);
    }



    public function update(string $table = null)
    {
        return $this->action('update', $table);
    }



    private function action(string $action, string $table = null)
    {
        $this->nomenclature  = require $this->oseAdmin->getOseDir() . 'data/nomenclatures.php';
        $this->donneesDefaut = require $this->oseAdmin->getOseDir() . 'data/donnees_par_defaut.php';

        foreach ($this->config as $tbl => $params) {
            $tbl = $params['table'];
            if (in_array($action, $params['context']) && ($tbl === $table || null === $table)) {
                $this->syncTable($tbl, $params);
            }
        }
    }



    private function syncTable(string $table, array $params)
    {
        $tableObject = $this->oseAdmin->getBdd()->getTable($table);
        $ddl         = $tableObject->getDdl();

        if ($tableObject->hasHistorique() && !isset($params['options']['histo-user-id'])){
            $params['options']['histo-user-id'] = $this->oseAdmin->getOseAppliId();
        }

        $hasImport = isset($ddl['columns']['SOURCE_ID']) && isset($ddl['columns']['SOURCE_CODE']);

        if (method_exists($this, $table)) {
            $data = $this->$table();
        } elseif (array_key_exists($table, $this->nomenclature)) {
            $data = $this->nomenclature[$table];
        } elseif (array_key_exists($table, $this->donneesDefaut)) {
            $data = $this->donneesDefaut[$table];
        } else {
            throw new \Exception('Données sources introuvables');
        }

        foreach ($data as $i => $line) {
            foreach ($line as $col => $val) {
                if (isset($ddl['columns'][$col]) && $ddl['columns'][$col]['type'] == 'DATE' && !empty($val) && is_string($val)) {
                    $data[$i][$col] = \DateTime::createFromFormat('Y-m-d', $val);
                }
            }

            if ($hasImport) {
                if (!isset($data[$i]['SOURCE_ID'])) {
                    $data[$i]['SOURCE_ID'] = $this->oseAdmin->getSourceOseId();
                }
            }
        }

        echo str_pad($table, 31, ' '); // provisoire
        //$this->oseAdmin->getConsole()->println($tbl);
        $result = $tableObject->merge(
            $data,
            isset($params['key']) ? $params['key'] : 'ID',
            isset($params['options']) ? $params['options'] : []
        );
        echo 'Insert: '.$result['insert'].', Update: '.$result['update'].', Delete: '.$result['delete'];
        echo "\n";
    }



    private function getAnneeCourante(): int
    {
        $now      = new \DateTime();
        $year     = (int)$now->format('Y');
        $mois     = (int)$now->format('m');
        $anneeRef = $year;
        if ($mois < 9) $anneeRef--;

        return $anneeRef;
    }



    public function ANNEE()
    {
        $annees = [];
        for ($a = 1950; $a < 2100; $a++) {
            $dateDebut = \DateTime::createFromFormat('Y-m-d:H:i:s', $a . '-09-01:00:00:00');
            $dateFin   = \DateTime::createFromFormat('Y-m-d:H:i:s', ($a + 1) . '-08-31:00:00:00');

            $anneeRef = $this->getAnneeCourante();
            $active   = ($a >= $anneeRef && $a < $anneeRef + 3);

            $annees[$a] = [
                'ID'         => $a,
                'LIBELLE'    => $a . '/' . ($a + 1),
                'DATE_DEBUT' => $dateDebut,
                'DATE_FIN'   => $dateFin,
                'ACTIVE'     => $active,
                'TAUX_HETD'  => null,
            ];
        }

        return $annees;
    }



    public function ETAT_SORTIE()
    {
        return require $this->oseAdmin->getOseDir() . 'data/etats_sortie.php';
    }



    public function CATEGORIE_PRIVILEGE()
    {
        $data       = require $this->oseAdmin->getOseDir() . 'data/privileges.php';
        $categories = [];
        foreach ($data as $code => $record) {
            $categories[] = [
                'CODE'    => $code,
                'LIBELLE' => $record['libelle'],
                'ORDRE'   => count($categories) + 1,
            ];
        }

        return $categories;
    }



    public function PRIVILEGE()
    {
        $data       = require $this->oseAdmin->getOseDir() . 'data/privileges.php';
        $privileges = [];
        foreach ($data as $code => $record) {
            $io = 0;
            foreach ($record['privileges'] as $pcode => $plib) {
                $io++;
                $privileges[] = [
                    'CATEGORIE_ID' => $code,
                    'CODE'         => $pcode,
                    'LIBELLE'      => $plib,
                    'ORDRE'        => $io,
                ];
            }
        }

        return $privileges;
    }



    public function FORMULE()
    {
        $data     = $this->nomenclature['FORMULE'];
        $formules = [];
        foreach ($data as $id => $formule) {
            $formule['ID']             = $id;
            $formule['PROCEDURE_NAME'] = 'CALCUL_RESULTAT';
            for ($i = 1; $i < 6; $i++) {
                if (!isset($formule['I_PARAM_' . $i . '_LIBELLE'])) $formule['I_PARAM_' . $i . '_LIBELLE'] = null;
                if (!isset($formule['VH_PARAM_' . $i . '_LIBELLE'])) $formule['VH_PARAM_' . $i . '_LIBELLE'] = null;
            }
            $formules[] = $formule;
        }

        return $formules;
    }



    public function FORMULE_TEST_STRUCTURE()
    {
        $data       = require $this->oseAdmin->getOseDir() . 'data/formule_test_structures.php';
        $structures = [];
        foreach ($data as $id => $structure) {
            $structures[] = [
                'ID'         => $id,
                'LIBELLE'    => $structure['libelle'],
                'UNIVERSITE' => $structure['universite'],
            ];
        }

        return $structures;
    }



    public function PLAFOND()
    {
        $data     = require $this->oseAdmin->getOseDir() . 'data/plafonds.php';
        $plafonds = [];

        foreach ($data['plafonds'] as $code => $libelle) {
            $plafond    = [
                'CODE'    => $code,
                'LIBELLE' => $libelle,
            ];
            $plafonds[] = $plafond;
        }

        return $plafonds;
    }



    public function PLAFOND_ETAT()
    {
        $data     = require $this->oseAdmin->getOseDir() . 'data/plafonds.php';
        $plafonds = [];

        foreach ($data['etats'] as $code => $libelle) {
            $plafond    = [
                'CODE'    => $code,
                'LIBELLE' => $libelle,
            ];
            $plafonds[] = $plafond;
        }

        return $plafonds;
    }



    public function INDICATEUR()
    {
        $data        = require $this->oseAdmin->getOseDir() . 'data/indicateurs.php';
        $indicateurs = [];
        $ordre       = 0;
        foreach ($data as $numero => $indicateur) {
            $indicateur['NUMERO'] = $numero;
            $indicateur['ORDRE']  = $ordre++;
            $indicateurs[]        = $indicateur;
        }

        return $indicateurs;
    }



    public function WF_ETAPE()
    {
        $data   = require $this->oseAdmin->getOseDir() . 'data/workflow_etapes.php';
        $etapes = [];
        $ordre  = 1;
        foreach ($data as $code => $etape) {
            $etape['CODE']  = $code;
            $etape['ORDRE'] = $ordre++ * 10;
            $etapes[]       = $etape;
        }

        return $etapes;
    }



    public function PARAMETRE()
    {
        $bdd = $this->oseAdmin->getBdd();

        $data = require $this->oseAdmin->getOseDir() . 'data/parametres.php';

        $queries = [
            'etablissement' =>
                'SELECT id valeur FROM etablissement WHERE source_code = :valeur AND histo_destruction IS NULL',

            'formule' =>
                'SELECT id valeur FROM formule WHERE package_name = :valeur',

            'domaine_fonctionnel_ens_ext' =>
                'SELECT id valeur FROM domaine_fonctionnel WHERE source_code = :valeur AND histo_destruction IS NULL',

            'scenario_charges_services' =>
                'SELECT id valeur FROM scenario WHERE libelle = :valeur AND histo_destruction IS NULL',

            'es_winpaie'       => 'SELECT id valeur FROM etat_sortie WHERE code = :valeur',
            'es_services_pdf'  => 'SELECT id valeur FROM etat_sortie WHERE code = :valeur',
            'es_etat_paiement' => 'SELECT id valeur FROM etat_sortie WHERE code = :valeur',
        ];

        foreach ($queries as $nom => $query) {
            $val = isset($data[$nom]['VALEUR']) ? $data[$nom]['VALEUR'] : null;
            $res = $bdd->select($query, ['valeur' => $val], $bdd::FETCH_ONE);
            if (isset($res['VALEUR'])){
                $data[$nom]['VALEUR'] = (string)$res['VALEUR'];
            }
        }

        $data['annee']['VALEUR']        = (string)$this->getAnneeCourante();
        $data['annee_import']['VALEUR'] = (string)$this->getAnneeCourante();
        $data['oseuser']['VALEUR']      = (string)$this->oseAdmin->getOseAppliId();

        $parametres = [];
        foreach ($data as $nom => $params) {
            $params['NOM'] = $nom;
            $parametres[]  = $params;
        }

        return $parametres;
    }

}