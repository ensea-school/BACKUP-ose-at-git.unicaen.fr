<?php





class DataGen
{
    /**
     * @var OseAdmin
     */
    private $oseAdmin;

    private $config = [
        /* Obligatoire au début */
        'UTILISATEUR'                => [
            'title'   => 'Utilisateurs',
            'key'     => 'USERNAME',
            'options' => ['update-ignore-cols' => ['EMAIL', 'PASSWORD'], 'delete' => false],
        ],
        'SOURCE'                     => [
            'title'   => 'Sources de données',
            'key'     => 'CODE',
            'options' => ['delete' => false],
        ],



        /* Nomenclatures fixes et jamais paramétrables */
        'CIVILITE'                   => [
            'title' => 'Civilités',
            'key'   => ['LIBELLE_COURT'],
        ],
        'PLAFOND'                    => [
            'title' => 'Plafonds',
            'key'   => 'CODE',
        ],
        'PLAFOND_ETAT'               => [
            'title' => 'États des plafonds',
            'key'   => 'CODE',
        ],
        'TYPE_VOLUME_HORAIRE'        => [
            'title' => 'Types de volumes horaires',
            'key'   => 'CODE',
        ],
        'ETAT_VOLUME_HORAIRE'        => [
            'title' => 'États de volumes horaires',
            'key'   => 'CODE',
        ],
        'PERIMETRE'                  => [
            'title' => 'Périmètres des rôles',
            'key'   => 'CODE',
        ],
        'TYPE_VALIDATION'            => [
            'title' => 'Types de validation',
            'key'   => 'CODE',
        ],
        'TBL'                        => [
            'title' => 'Tableaux de bord',
            'key'   => 'TBL_NAME',
        ],
        'WF_ETAPE'                   => [
            'title' => 'Étapes de workflow',
            'key'   => 'CODE',
        ],
        'TYPE_AGREMENT'              => [
            'title' => 'Types d\'agréments',
            'key'   => 'CODE',
        ],
        'TYPE_CONTRAT'               => [
            'title' => 'Types de contrats',
            'key'   => 'CODE',
        ],
        'CATEGORIE_PRIVILEGE'        => [
            'title' => 'Catégories de privilèges',
            'key'   => 'CODE',
        ],
        'PRIVILEGE'                  => [
            'title'   => 'Privilèges',
            'key'     => ['CATEGORIE_ID', 'CODE'],
            'options' => ['columns' => ['CATEGORIE_ID' => ['transformer' => 'SELECT id FROM categorie_privilege WHERE code = %s']]],
        ],
        'INDICATEUR'                 => [
            'title' => 'Indicateurs',
            'key'   => 'NUMERO',
        ],
        'FORMULE'                    => [
            'title' => 'Formules',
            'key'   => 'ID',
        ],
        'FORMULE_TEST_STRUCTURE'     => [
            'title' => 'Structures de tests de formules',
            'key'   => 'ID',
        ],
        //        'TYPE_HEURES'            => [
        //             'title' => 'Types d\'heures',
        //             'key'   => 'CODE',
        //             'options' => ['columns' => ['TYPE_HEURES_ELEMENT_ID' => ['transformer' => 'COALESCE((SELECT id FROM type_heures WHERE code = %s),type_heures_id_seq.curval)']]],
        //        ],
        'TYPE_INTERVENANT'           => [
            'title' => 'Types d\'intervenants',
            'key'   => 'CODE',
        ],
        // PERIODE
        // TAUX_HORAIRE_HETD
        // TYPE_RESSOURCE



        /* Nomenclatures partiellement paramétrables (certaines colonnes) */
        'ANNEE'                      => [
            'title'   => 'Années',
            'key'     => 'ID',
            'options' => ['update-ignore-cols' => ['ACTIVE', 'TAUX_HETD']],
        ],
        // PARAMETRE
        // REGLE_STRUCTURE_VALIDATION



        /* Tables avec paramétrages pré-configurés (certaines colonnes + nouveaux enregistrements) */
        'IMPORT_TABLES'              => [
            'title'   => 'Configuration de synchro des tables',
            'key'     => 'TABLE_NAME',
            'options' => ['update' => false, 'delete' => false],
        ],
        'CC_ACTIVITE'                => [
            'title'   => 'Activités (budget)',
            'key'     => 'CODE',
            'options' => ['update' => false, 'delete' => false],
        ],
        /*'TYPE_INTERVENTION'         => [
    'title'   => 'Types d\'intervention',
    'key'     => 'CODE',
    'options' => ['update' => false, 'delete' => false], // Attention : INSERT à FAIRE seulement à l'install!!!
],*/
        'SCENARIO'                   => [
            'title'   => 'Scénarios',
            'key'     => 'LIBELLE',
            'options' => ['delete' => false],
        ],
        'ETAT_SORTIE'                => [
            'title'   => 'États de sortie',
            'key'     => 'CODE',
            'options' => ['update' => false, 'delete' => false],
        ],
        //  MODELE_CONTRAT
        // ROLE
        // ROLE_PRIVILEGE
        // AFFECTATION



        /* Jeu de données de configuration par défaut (tout perso) */
        'PAYS'                       => [
            'title'   => 'Pays',
            'key'     => 'SOURCE_CODE',
            'options' => ['update' => false, 'delete' => false], // Attention : INSERT à FAIRE seulement à l'install!!!
        ],
        'DEPARTEMENT'                => [
            'title'   => 'Départements',
            'key'     => 'SOURCE_CODE',
            'options' => ['update' => false, 'delete' => false], // Attention : INSERT à FAIRE seulement à l'install!!!
        ],
        'ETABLISSEMENT'              => [
            'title'   => 'Établissements',
            'key'     => 'SOURCE_CODE',
            'options' => ['update' => false, 'delete' => false], // Attention : INSERT à FAIRE seulement à l'install!!!
        ],
        'CORPS'                      => [
            'title'   => 'Corps',
            'key'     => 'SOURCE_CODE',
            'options' => ['update' => false, 'delete' => false], // Attention : INSERT à FAIRE seulement à l'install!!!
        ],
        'GRADE'                      => [
            'title'   => 'grades',
            'key'     => 'SOURCE_CODE',
            'options' => ['update'  => false, 'delete' => false,
                          'columns' => ['CORPS_ID' => ['transformer' => 'SELECT id FROM corps WHERE source_code = %s']]], // Attention : INSERT à FAIRE seulement à l'install!!!
        ],
        'DISCIPLINE'                 => [
            'title'   => 'Disciplines',
            'key'     => 'SOURCE_CODE',
            'options' => ['update' => false, 'delete' => false], // Attention : INSERT à FAIRE seulement à l'install!!!
        ],
        'DOMAINE_FONCTIONNEL'        => [
            'title'   => 'Domaines fonctionnels',
            'key'     => 'SOURCE_CODE',
            'options' => ['update' => false, 'delete' => false], // Attention : INSERT à FAIRE seulement à l'install!!!
        ],
        // FONCTION_REFERENTIEL
        'MOTIF_MODIFICATION_SERVICE' => [
            'title'   => 'Motifs de modification de service',
            'key'     => 'CODE',
            'options' => ['update' => false, 'delete' => false], // Attention : INSERT à FAIRE seulement à l'install!!!
        ],
        'MOTIF_NON_PAIEMENT'         => [
            'title'   => 'Motifs de non paiement',
            'key'     => 'CODE',
            'options' => ['update' => false, 'delete' => false], // Attention : INSERT à FAIRE seulement à l'install!!!
        ],
        // TYPE_PIECE_JOINTE
        // TYPE_PIECE_JOINTE_STATUT
        // STATUT_INTERVENANT
        // STATUT_PRIVILEGE
        // TYPE_AGREMENT_STATUT
        // WF_ETAPE_DEP
    ];



    public function __construct(OseAdmin $oseAdmin)
    {
        $this->oseAdmin = $oseAdmin;
    }



    public function update(string $table = null)
    {
        foreach ($this->config as $tbl => $params) {
            if ($tbl === $table || null === $table) {
                if (isset($params['title'])) {
                    echo $params['title'] . "\n"; // provisoire
                    //$this->oseAdmin->getConsole()->println($params['title']);
                }
                $data = $this->$tbl();
                $this->oseAdmin->getBdd()->getTable($tbl)->merge(
                    $data,
                    isset($params['key']) ? $params['key'] : 'ID',
                    isset($params['options']) ? $params['options'] : []
                );
            }
        }
    }



    private function addHistorique(array &$data)
    {
        $data['HISTO_CREATION']        = new \DateTime();
        $data['HISTO_CREATEUR_ID']     = $this->oseAdmin->getOseAppliId();
        $data['HISTO_MODIFICATION']    = new \DateTime();
        $data['HISTO_MODIFICATEUR_ID'] = $this->oseAdmin->getOseAppliId();
    }



    private function addSourceOse(array &$data, string $code)
    {
        $data['SOURCE_ID']   = $this->oseAdmin->getSourceOseId();
        $data['SOURCE_CODE'] = $code;
    }



    public function ANNEE()
    {
        $annees = [];
        for ($a = 1950; $a < 2100; $a++) {
            $dateDebut = \DateTime::createFromFormat('Y-m-d:H:i:s', $a . '-09-01:00:00:00');
            $dateFin   = \DateTime::createFromFormat('Y-m-d:H:i:s', ($a + 1) . '-08-31:00:00:00');

            $now      = new \DateTime();
            $year     = (int)$now->format('Y');
            $mois     = (int)$now->format('m');
            $anneeRef = $year;
            if ($mois < 9) $anneeRef--;
            $active = ($a >= $anneeRef && $a < $anneeRef + 3);

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



    public function CIVILITE()
    {
        return require $this->oseAdmin->getOseDir() . 'data/civilites.php';
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



    public function CC_ACTIVITE()
    {
        $data      = require $this->oseAdmin->getOseDir() . 'data/cc_activites.php';
        $activites = [];

        foreach ($data as $code => $activite) {
            $activite['CODE'] = $code;
            $this->addHistorique($activite);
            $activites[] = $activite;
        }

        return $activites;
    }



    public function FORMULE()
    {
        $data     = require $this->oseAdmin->getOseDir() . 'data/formules.php';
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



    public function IMPORT_TABLES()
    {
        $data   = require $this->oseAdmin->getOseDir() . 'data/import_tables.php';
        $tables = [];
        foreach ($data as $name => $table) {
            $table['TABLE_NAME'] = $name;
            $tables[]            = $table;
        }

        return $tables;
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



    public function TYPE_VOLUME_HORAIRE()
    {
        return require $this->oseAdmin->getOseDir() . 'data/types_volumes_horaires.php';
    }



    public function ETAT_VOLUME_HORAIRE()
    {
        return require $this->oseAdmin->getOseDir() . 'data/etats_volumes_horaires.php';
    }



    public function PERIMETRE()
    {
        $data       = require $this->oseAdmin->getOseDir() . 'data/perimetres.php';
        $perimetres = [];
        foreach ($data as $CODE => $LIBELLE) {
            $perimetres[] = compact('CODE', 'LIBELLE');
        }

        return $perimetres;
    }



    public function SOURCE()
    {
        return require $this->oseAdmin->getOseDir() . 'data/sources.php';
    }



    public function TBL()
    {
        return require $this->oseAdmin->getOseDir() . 'data/tbl.php';
    }



    public function TYPE_VALIDATION()
    {
        $data            = require $this->oseAdmin->getOseDir() . 'data/type_validations.php';
        $typesValidation = [];
        foreach ($data as $CODE => $LIBELLE) {
            $typesValidation[] = compact('CODE', 'LIBELLE');
        }

        return $typesValidation;
    }



    public function UTILISATEUR()
    {
        return require $this->oseAdmin->getOseDir() . 'data/utilisateurs.php';
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



    public function CORPS()
    {
        $data  = require $this->oseAdmin->getOseDir() . 'data/corps.php';
        $corps = [];
        foreach ($data as $code => $corp) {
            $this->addHistorique($corp);
            $this->addSourceOse($corp, $code);
            $corps[] = $corp;
        }

        return $corps;
    }



    public function PAYS()
    {
        $data = require $this->oseAdmin->getOseDir() . 'data/pays.php';
        $list = [];
        foreach ($data as $code => $item) {
            $this->addHistorique($item);
            $this->addSourceOse($item, $code);
            if (isset($item['VALIDITE_DEBUT'])) {
                $item['VALIDITE_DEBUT'] = \DateTime::createFromFormat('Y-m-d', $item['VALIDITE_DEBUT']);
            }
            if (isset($item['VALIDITE_FIN'])) {
                $item['VALIDITE_FIN'] = \DateTime::createFromFormat('Y-m-d', $item['VALIDITE_FIN']);
            }
            $list[] = $item;
        }

        return $list;
    }



    public function DEPARTEMENT()
    {
        $data         = require $this->oseAdmin->getOseDir() . 'data/departements.php';
        $departements = [];
        foreach ($data as $code => $departement) {
            $departement['CODE'] = $code;
            $this->addHistorique($departement);
            $this->addSourceOse($departement, $code);
            $departements[] = $departement;
        }

        return $departements;
    }



    public function DISCIPLINE()
    {
        $data        = require $this->oseAdmin->getOseDir() . 'data/disciplines.php';
        $disciplines = [];
        foreach ($data as $code => $discipline) {
            $this->addHistorique($discipline);
            $this->addSourceOse($discipline, $code);
            $disciplines[] = $discipline;
        }

        return $disciplines;
    }



    public function DOMAINE_FONCTIONNEL()
    {
        $data                 = require $this->oseAdmin->getOseDir() . 'data/domaines_fonctionnels.php';
        $domainesFonctionnels = [];
        foreach ($data as $code => $domaineFonctionnel) {
            $this->addHistorique($domaineFonctionnel);
            $this->addSourceOse($domaineFonctionnel, $code);
            $domainesFonctionnels[] = $domaineFonctionnel;
        }

        return $domainesFonctionnels;
    }



    public function ETABLISSEMENT()
    {
        $data           = require $this->oseAdmin->getOseDir() . 'data/etablissements.php';
        $etablissements = [];
        foreach ($data as $code => $etablissement) {
            $this->addHistorique($etablissement);
            $this->addSourceOse($etablissement, $code);
            $etablissements[] = $etablissement;
        }

        return $etablissements;
    }



    public function GRADE()
    {
        $data   = require $this->oseAdmin->getOseDir() . 'data/grades.php';
        $grades = [];
        foreach ($data as $code => $grade) {
            $this->addHistorique($grade);
            $this->addSourceOse($grade, $code);
            $grades[] = $grade;
        }

        return $grades;
    }



    public function MOTIF_MODIFICATION_SERVICE()
    {
        $data = require $this->oseAdmin->getOseDir() . 'data/motifs_modification_service.php';
        $list = [];
        foreach ($data as $code => $item) {
            $item['CODE'] = $code;
            $this->addHistorique($item);
            $list[] = $item;
        }

        return $list;
    }



    public function MOTIF_NON_PAIEMENT()
    {
        $data = require $this->oseAdmin->getOseDir() . 'data/motifs_non_paiement.php';
        $list = [];
        foreach ($data as $code => $item) {
            $item['CODE'] = $code;
            $this->addHistorique($item);
            $list[] = $item;
        }

        return $list;
    }



    public function TYPE_INTERVENTION()
    {
        $data  = require $this->oseAdmin->getOseDir() . 'data/types_intervention.php';
        $list  = [];
        $ordre = 1;
        foreach ($data as $code => $item) {
            $item['CODE']                          = $code;
            $item['ORDRE']                         = $ordre++;
            $item['TYPE_INTERVENTION_MAQUETTE_ID'] = $ordre++;
            $this->addHistorique($item);
            $list[] = $item;
        }

        return $list;
    }



    public function TYPE_AGREMENT()
    {
        $data = require $this->oseAdmin->getOseDir() . 'data/types_agrements.php';
        $list = [];
        foreach ($data as $code => $libelle) {
            $ta = ['CODE' => $code, 'LIBELLE' => $libelle];
            $this->addHistorique($ta);
            $list[] = $ta;
        }

        return $list;
    }



    public function TYPE_CONTRAT()
    {
        $data = require $this->oseAdmin->getOseDir() . 'data/types_contrats.php';
        $list = [];
        foreach ($data as $code => $libelle) {
            $tc = ['CODE' => $code, 'LIBELLE' => $libelle];
            $this->addHistorique($tc);
            $list[] = $tc;
        }

        return $list;
    }



    public function TYPE_HEURES()
    {
        $data  = require $this->oseAdmin->getOseDir() . 'data/types_heures.php';
        $list  = [];
        $ordre = 1;
        foreach ($data as $code => $d) {
            $th = [
                'CODE'                     => $code,
                'LIBELLE_COURT'            => $d['libelle-court'],
                'LIBELLE_LONG'             => $d['libelle-long'],
                'ORDRE'                    => $ordre++,
                'TYPE_HEURES_ELEMENT_ID'   => $d['type-heures-element'],
                'ELIGIBLE_CENTRE_COUT_EP'  => $d['eligible-centre-cout-ep'],
                'ELIGIBLE_EXTRACTION_PAIE' => $d['eligible-extraction-paie'],
                'ENSEIGNEMENT'             => $d['enseignement'],
            ];
            $this->addHistorique($th);
            $list[] = $th;
        }

        return $list;
    }



    public function TYPE_INTERVENANT()
    {
        $data = require $this->oseAdmin->getOseDir() . 'data/types_intervenants.php';
        $list = [];
        foreach ($data as $code => $libelle) {
            $tc = ['CODE' => $code, 'LIBELLE' => $libelle];
            $this->addHistorique($tc);
            $list[] = $tc;
        }

        return $list;
    }



    public function SCENARIO()
    {
        $data = require $this->oseAdmin->getOseDir() . 'data/scenarios.php';
        $list = [];
        foreach ($data as $libelle => $item) {
            $item['LIBELLE'] = $libelle;
            $this->addHistorique($item);
            $list[] = $item;
        }

        return $list;
    }
}