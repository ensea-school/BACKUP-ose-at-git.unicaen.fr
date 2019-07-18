<?php





class DataGen
{
    /**
     * @var OseAdmin
     */
    private $oseAdmin;

    private $config = [
        'ANNEE'               => [
            'title'   => 'Années',
            'key'     => 'ID',
            'options' => ['update-ignore-cols' => ['ACTIVE', 'TAUX_HETD']],
        ],
        'ETAT_SORTIE'         => [
            'title'   => 'États de sortie',
            'key'     => 'CODE',
            'options' => ['update' => false, 'delete' => false],
        ],
        'CATEGORIE_PRIVILEGE' => [
            'title' => 'Catégories de privilèges',
            'key'   => 'CODE',
        ],
        'PRIVILEGE'           => [
            'title'   => 'Privilèges',
            'key'     => ['CATEGORIE_ID', 'CODE'],
            'options' => ['columns' => ['CATEGORIE_ID' => ['transformer' => 'SELECT id FROM categorie_privilege WHERE code = %s']]],
        ],
        'INDICATEUR'          => [
            'title' => 'Indicateurs',
            'key'   => ['NUMERO'],
        ],
        'CIVILITE'            => [
            'title'   => 'Civilités',
            'key'     => ['LIBELLE_COURT'],
            'options' => ['delete' => false],
        ],
        'FORMULE'             => [
            'title' => 'Formules',
            'key'   => ['ID'],
        ],
        'PLAFOND'             => [
            'title' => 'Plafonds',
            'key'   => 'CODE',
        ],
    ];



    /*
    'AFFECTATION'                 => "utilisateur_id IN (SELECT id FROM utilisateur WHERE username='" . self::OSE_USER . "')",
    'CC_ACTIVITE'                 => '',
    'ETAT_VOLUME_HORAIRE'         => '',
    'FORMULE_TEST_STRUCTURE'      => '',
    'GROUPE'                      => '',
    'IMPORT_TABLES'               => '',
    'MESSAGE'                     => '',
    'MODELE_CONTRAT'              => "libelle = 'Modèle par défaut'",
    'PARAMETRE'                   => '',
    'PERIMETRE'                   => '',
    'PERIODE'                     => '',
    'REGLE_STRUCTURE_VALIDATION'  => '',
    'ROLE'                        => '',
    'ROLE_PRIVILEGE'              => '',
    'SCENARIO'                    => 'structure_id IS NULL',
    'SOURCE'                      => "code='OSE'",
    'TAUX_HORAIRE_HETD'           => '',
    'TBL'                         => '',
    'TYPE_AGREMENT'               => '',
    'TYPE_AGREMENT_STATUT'        => '',
    'TYPE_CONTRAT'                => '',
    'TYPE_HEURES'                 => '',
    'TYPE_INTERVENANT'            => '',
    'TYPE_INTERVENTION'           => "code IN ('CM','TD','TP')",
    'TYPE_RESSOURCE'              => '',
    'TYPE_VALIDATION'             => '',
    'TYPE_VOLUME_HORAIRE'         => '',
    'UTILISATEUR'                 => "username = '" . self::OSE_USER . "'",
    'WF_ETAPE'                    => '',
    'WF_ETAPE_DEP'                => '',

    'PAYS'                        => '',
    'DEPARTEMENT'                 => '',
    'DISCIPLINE'                  => '',
    'DOMAINE_FONCTIONNEL'         => '',
    'ETABLISSEMENT'               => '',
    'CORPS'                       => '',
    'GRADE'                       => 'corps_id in (select c.id from corps c where c.histo_destruction is null)',
    'FONCTION_REFERENTIEL'        => '',
    'MOTIF_MODIFICATION_SERVICE'  => '',
    'MOTIF_NON_PAIEMENT'          => '',
    'STATUT_INTERVENANT'          => '',
    'STATUT_PRIVILEGE'            => 'statut_id IN (SELECT si.id FROM statut_intervenant si WHERE si.histo_destruction IS NULL)',
    'TYPE_PIECE_JOINTE'           => '',
    'TYPE_PIECE_JOINTE_STATUT'    => '',
    */

    public function __construct(OseAdmin $oseAdmin)
    {
        $this->oseAdmin = $oseAdmin;
    }



    public function update()
    {
        foreach ($this->config as $table => $params) {
            $this->oseAdmin->getConsole()->println('  * ' . $params['title'] . ' ...');
            $data = $this->$table();
            $this->oseAdmin->getBdd()->getTable($table)->merge(
                $data,
                $params['key'],
                isset($params['options']) ? $params['options'] : []
            );
        }
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



    public function PLAFOND()
    {
        $data = require $this->oseAdmin->getOseDir() . 'data/plafonds.php';
        $plafonds = [];

        foreach( $data['plafonds'] as $code => $libelle ){
            $plafond = [
                'CODE' => $code,
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
}