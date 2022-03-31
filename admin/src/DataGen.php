<?php





class DataGen
{
    use \BddAdmin\Logger\LoggerAwareTrait;

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

    private $actions       = [
        'install'    => 'Insertion des données',
        'update'     => 'Contrôle et mise à jour des données',
        'privileges' => 'Mise à jour des privilèges dans la base de données',
    ];

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
            'table'   => 'PLAFOND_ETAT',
            'context' => ['install', 'update'],
            'key'     => 'ID',
        ],
        [
            'table'   => 'PLAFOND_PERIMETRE',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
        ],
        [
            'table'   => 'PLAFOND',
            'context' => ['install', 'update'],
            'options' => ['update' => false, 'delete' => false],
            'key'     => 'NUMERO',
        ],
        [
            'table'   => 'TYPE_NOTE',
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
            'context' => ['install', 'update', 'privileges'],
            'key'     => 'CODE',
        ],
        [
            'table'   => 'PRIVILEGE',
            'context' => ['install', 'update', 'privileges'],
            'key'     => ['CATEGORIE_ID', 'CODE'],
            'options' => ['columns' => ['CATEGORIE_ID' => ['transformer' => 'SELECT id FROM categorie_privilege WHERE code = %s']]],
        ],
        [
            'table'   => 'TYPE_INDICATEUR',
            'context' => ['install', 'update'],
            'key'     => 'ID',
        ],
        [
            'table'   => 'INDICATEUR',
            'context' => ['install', 'update'],
            'key'     => ['TYPE_INDICATEUR_ID', 'NUMERO'],
        ],
        [
            'table'   => 'FORMULE',
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
            'context' => ['install'],
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
        [
            'table'   => 'DOSSIER_CHAMP_AUTRE_TYPE',
            'context' => ['install', 'update'],
            'key'     => 'CODE',
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
        [
            'table'   => 'DOSSIER_CHAMP_AUTRE',
            'context' => ['install', 'update'],
            'key'     => 'ID',
            'options' => ['update-ignore-cols' => ['LIBELLE', 'DOSSIER_CHAMP_AUTRE_TYPE_ID', 'CONTENU', 'DESCRIPTION', 'OBLIGATOIRE']],
        ],


        /* Tables avec paramétrages pré-configurés (certaines colonnes + nouveaux enregistrements) */
        [
            'table'   => 'ADRESSE_NUMERO_COMPL',
            'context' => ['install'],
            'key'     => ['CODE'],
        ],
        [
            'table'   => 'IMPORT_TABLES',
            'context' => ['install', 'update'],
            'key'     => 'TABLE_NAME',
            //'options' => ['update' => true, 'delete' => true],
            'options' => ['update-ignore-cols' => ['SYNC_FILTRE', 'SYNC_ENABLED', 'SYNC_JOB', 'SYNC_HOOK_BEFORE', 'SYNC_HOOK_AFTER']],
        ],
        [
            'table'   => 'CC_ACTIVITE',
            'context' => ['install'],
            'key'     => 'CODE',
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
            'options' => ['update'           => true, 'delete' => false,
                          'update-cols'      => ['CSV_PARAMS', 'CSV_TRAITEMENT', 'PDF_TRAITEMENT'],
                          'update-only-null' => ['CSV_PARAMS', 'CSV_TRAITEMENT', 'PDF_TRAITEMENT'],
            ],
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
            'table'   => 'VOIRIE',
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
            'table'   => 'STATUT',
            'context' => ['install'],
            'key'     => 'CODE',
            'options' => ['columns' => ['TYPE_INTERVENANT_ID' => ['transformer' => 'SELECT id FROM type_intervenant WHERE code = %s']]],
        ],
        [
            'table'   => 'TYPE_PIECE_JOINTE',
            'context' => ['install'],
            'key'     => 'CODE',
        ],
        [
            'table'   => 'TYPE_PIECE_JOINTE_STATUT',
            'context' => ['install'],
            'key'     => ['STATUT_ID', 'TYPE_PIECE_JOINTE_ID'],
            'options' => ['columns' => [
                'STATUT_ID'            => ['transformer' => 'SELECT id FROM statut WHERE histo_destruction IS NULL AND code = %s'],
                'TYPE_PIECE_JOINTE_ID' => ['transformer' => 'SELECT id FROM type_piece_jointe WHERE histo_destruction IS NULL AND code = %s'],
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
        $this->setLogger($oseAdmin->getBdd()->getLogger());
    }



    public function install(string $table = null)
    {
        return $this->action('install', $table);
    }



    public function update(string $table = null)
    {
        return $this->action('update', $table);
    }



    public function updatePrivileges()
    {
        return $this->action('privileges');
    }



    private function action(string $action, string $table = null)
    {
        $this->logBegin($this->actions[$action]);
        $this->nomenclature  = require $this->oseAdmin->getOseDir() . 'data/nomenclatures.php';
        $this->donneesDefaut = require $this->oseAdmin->getOseDir() . 'data/donnees_par_defaut.php';

        foreach ($this->config as $tbl => $params) {
            $tbl = $params['table'];
            if (in_array($action, $params['context']) && ($tbl === $table || null === $table)) {
                $this->syncTable($tbl, $params);
            }
        }

        if (!$table) {
            $this->logMsg('Mise à jour du point d\'indice pour les HETD ...', true);
            $this->oseAdmin->getBdd()->exec('BEGIN OSE_FORMULE.UPDATE_ANNEE_TAUX_HETD; END;');
        }
        $this->logEnd();
    }



    private function syncTable(string $table, array $params)
    {
        $tableObject = $this->oseAdmin->getBdd()->getTable($table);
        $ddl         = $tableObject->getDdl();

        if ($tableObject->hasHistorique() && !isset($params['options']['histo-user-id'])) {
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
                if (isset($ddl['columns'][$col]) && $ddl['columns'][$col]['type'] == \BddAdmin\Bdd::TYPE_DATE && !empty($val) && is_string($val)) {
                    $data[$i][$col] = \DateTime::createFromFormat('Y-m-d H:i:s', $val);
                }
            }

            if ($hasImport) {
                if (!isset($data[$i]['SOURCE_ID'])) {
                    $data[$i]['SOURCE_ID'] = $this->oseAdmin->getSourceOseId();
                }
            }
        }

        $result = $tableObject->merge(
            $data,
            isset($params['key']) ? $params['key'] : 'ID',
            isset($params['options']) ? $params['options'] : []
        );
        if ($result['insert'] + $result['update'] + $result['delete'] > 0) {
            $msg = str_pad($table, 31, ' ');
            $msg .= 'Insert: ' . $result['insert'] . ', Update: ' . $result['update'] . ', Delete: ' . $result['delete'];
            $this->logMsg($msg);
        }
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
            $dateDebut = \DateTime::createFromFormat('Y-m-d H:i:s', $a . '-09-01 00:00:00');
            $dateFin   = \DateTime::createFromFormat('Y-m-d H:i:s', ($a + 1) . '-08-31 00:00:00');

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



    public function DEPARTEMENT()
    {
        $departements = [];

        $r = fopen($this->oseAdmin->getOseDir() . 'data/departement.csv', 'r');
        $i = 0;
        while ($d = fgetcsv($r, 0, ',', '"')) {
            $i++;
            if ($i > 1) {
                $code = (string)$d[0];
                if (2 == strlen($code)) {
                    $code = '0' . $code;
                }
                $departements[] = [
                    'SOURCE_CODE' => $code,
                    'CODE'        => $code,
                    'LIBELLE'     => $d[6],
                ];
            }
        }

        fclose($r);

        return $departements;
    }



    public function IMPORT_TABLES()
    {
        $data = require $this->oseAdmin->getOseDir() . 'data/import_tables.php';

        $ordre = 0;
        $d     = [];
        foreach ($data as $table => $td) {
            $ordre++;
            $td['TABLE_NAME'] = $table;
            $td['ORDRE']      = $ordre;
            $d[]              = $td;
        }

        return $d;
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



    public function PLAFOND()
    {
        $data     = require $this->oseAdmin->getOseDir() . 'data/plafonds.php';
        $plafonds = [];

        foreach ($data['plafonds'] as $numero => $p) {
            $psql        = 'SELECT id FROM plafond_perimetre WHERE code = :code';
            $perimetreId = $this->oseAdmin->getBdd()->select($psql, ['code' => $p['perimetre']], ['fetch' => \BddAdmin\Bdd::FETCH_ONE])['ID'];
            $plafond     = [
                'NUMERO'               => $numero,
                'LIBELLE'              => $p['libelle'],
                'MESSAGE'              => $p['message'] ?? null,
                'PLAFOND_PERIMETRE_ID' => $perimetreId,
                'REQUETE'              => $p['requete'],
            ];
            $plafonds[]  = $plafond;
        }

        return $plafonds;
    }



    public function PLAFOND_ETAT()
    {
        $data     = require $this->oseAdmin->getOseDir() . 'data/plafonds.php';
        $plafonds = [];
        $id       = 1;
        foreach ($data['etats'] as $code => $pe) {
            $plafond    = [
                'ID'       => $id,
                'CODE'     => $code,
                'LIBELLE'  => $pe['libelle'],
                'BLOQUANT' => $pe['bloquant'],
            ];
            $plafonds[] = $plafond;
            $id++;
        }

        return $plafonds;
    }



    public function PLAFOND_PERIMETRE()
    {
        $data     = require $this->oseAdmin->getOseDir() . 'data/plafonds.php';
        $plafonds = [];
        $id       = 0;
        foreach ($data['perimetres'] as $code => $libelle) {
            $id++;
            $plafond    = [
                'ID'      => $id,
                'CODE'    => $code,
                'LIBELLE' => $libelle,
            ];
            $plafonds[] = $plafond;
        }

        return $plafonds;
    }



    public function TYPE_INDICATEUR()
    {
        $data        = require $this->oseAdmin->getOseDir() . 'data/indicateurs.php';
        $indicateurs = [];
        $ordre       = 0;
        foreach ($data as $libelle => $indicateur) {
            $idata         = [
                'ID'      => $indicateur['id'],
                'LIBELLE' => $libelle,
                'ORDRE'   => $ordre++,
            ];
            $indicateurs[] = $idata;
        }

        return $indicateurs;
    }



    public function INDICATEUR()
    {
        $data        = require $this->oseAdmin->getOseDir() . 'data/indicateurs.php';
        $indicateurs = [];
        $ordre       = 0;
        foreach ($data as $typeIndicateur) {
            foreach ($typeIndicateur['indicateurs'] as $numero => $idata) {
                $indicateur = [
                    'NUMERO'             => (int)$numero,
                    'ORDRE'              => $ordre++,
                    'TYPE_INDICATEUR_ID' => (int)$typeIndicateur['id'],
                ];
                foreach ($idata as $k => $v) {
                    $indicateur[strtoupper($k)] = $v;
                }
                $indicateurs[] = $indicateur;
            }
        }

        $pis = $this->oseAdmin->getBdd()->select('SELECT * FROM V_PLAFOND_INDICATEURS');
        foreach ($pis as $pi) {
            $indicateurs[] = $pi;
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

        foreach ($data as $nom => $params) {
            if (isset($params['QUERY'])) {
                $query = $params['QUERY'];

                $val = isset($data[$nom]['VALEUR']) ? $data[$nom]['VALEUR'] : null;
                $res = $bdd->select($query, ['valeur' => $val], ['fetch' => $bdd::FETCH_ONE]);
                if (isset($res['VALEUR'])) {
                    $data[$nom]['VALEUR'] = (string)$res['VALEUR'];
                }
                unset($data[$nom]['QUERY']);
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
