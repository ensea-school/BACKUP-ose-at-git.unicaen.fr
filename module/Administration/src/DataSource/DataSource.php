<?php

namespace Administration\DataSource;

use Unicaen\BddAdmin\BddAwareTrait;

class DataSource
{
    use BddAwareTrait;


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

        $r = fopen('data/departement.csv', 'r');
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
        $data = require 'data/import_tables.php';

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



    public function JOUR_FERIE()
    {
        $joursFeries = require 'data/jours_feries.php';

        $jfs = [];
        for ($a = 1950; $a < 2100; $a++) {
            foreach ($joursFeries as $date => $libelle) {
                if (strlen($date) == 5) {
                    $jfs[] = [
                        'LIBELLE'   => $libelle,
                        'DATE_JOUR' => \DateTime::createFromFormat('Y-m-d H:i:s', $a . '-' . $date . ' 00:00:00'),
                    ];
                }
            }
        }
        foreach ($joursFeries as $date => $libelle) {
            if (strlen($date) == 10) {
                $jfs[] = [
                    'LIBELLE'   => $libelle,
                    'DATE_JOUR' => \DateTime::createFromFormat('Y-m-d H:i:s', $date . ' 00:00:00'),
                ];
            }
        }

        return $jfs;
    }



    public function ETAT_SORTIE()
    {
        return require 'data/etats_sortie.php';
    }



    public function CATEGORIE_PRIVILEGE()
    {
        $data       = require 'data/privileges.php';
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
        $data       = require 'data/privileges.php';
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



    public function STATUT()
    {
        $donneesParDefaut = require 'data/donnees_par_defaut.php';
        $data             = $donneesParDefaut['STATUT'];

        $statuts = [];
        for ($a = 2010; $a <= 2099; $a++) {
            foreach ($data as $d) {
                $d['ANNEE_ID']              = $a;
                $d['HISTO_MODIFICATEUR_ID'] = null;
                $statuts[]                  = $d;
            }
        }

        return $statuts;
    }



    public function FONCTION_REFERENTIEL()
    {
        $donneesParDefaut = require 'data/donnees_par_defaut.php';
        $data             = $donneesParDefaut['FONCTION_REFERENTIEL'];

        $fonctions = [];
        for ($a = 2010; $a <= 2099; $a++) {
            foreach ($data as $d) {
                $d['ANNEE_ID']              = $a;
                $d['HISTO_MODIFICATEUR_ID'] = null;
                $fonctions[]                = $d;
            }
        }

        return $fonctions;
    }



    public function TYPE_PIECE_JOINTE_STATUT()
    {
        $donneesParDefaut = require 'data/donnees_par_defaut.php';
        $data             = $donneesParDefaut['TYPE_PIECE_JOINTE_STATUT'];

        $statuts = [];
        for ($a = 2010; $a <= 2099; $a++) {
            foreach ($data as $d) {
                $d['ANNEE_ID']              = $a;
                $d['HISTO_MODIFICATEUR_ID'] = null;
                $statuts[]                  = $d;
            }
        }

        return $statuts;
    }



    public function PLAFOND()
    {
        $data     = require 'data/plafonds.php';
        $plafonds = [];

        foreach ($data['plafonds'] as $numero => $p) {
            $psql        = 'SELECT id FROM plafond_perimetre WHERE code = :code';
            $perimetreId = $this->getBdd()->selectOne($psql, ['code' => $p['perimetre']], 'ID');
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
        $data     = require 'data/plafonds.php';
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
        $data     = require 'data/plafonds.php';
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



    public function TAUX_REMU(string $action)
    {
        $data = require 'data/taux_remu.php';

        $tauxRemu = [];

        foreach ($data as $code => $taux) {
            $taux = [
                'CODE'         => $code,
                'LIBELLE'      => $taux['libelle'],
                'TAUX_REMU_ID' => $taux['parent'] ?? null,
            ];

            if (!($action === 'update' && !in_array($code, ['TLD', 'SMIC']))) {
                $tauxRemu[] = $taux;
            }

        }

        return $tauxRemu;
    }



    public function TAUX_REMU_VALEUR(string $action)
    {
        $data = require 'data/taux_remu.php';

        $tauxValeurs = [];

        foreach ($data as $code => $taux) {
            foreach ($taux['valeurs'] as $dateEffet => $valeur) {
                $dateEffet = \DateTime::createFromFormat('d/m/Y', $dateEffet);
                $dateEffet->setTime(0, 0, 0);
                $tauxValeur = [
                    'TAUX_REMU_ID' => $code,
                    'DATE_EFFET'   => $dateEffet,
                    'VALEUR'       => $valeur,
                ];
                if (!($action === 'update' && !in_array($code, ['TLD', 'SMIC']))) {
                    $tauxValeurs[] = $tauxValeur;
                }
            }
        }

        return $tauxValeurs;
    }



    public function TYPE_MISSION()
    {
        $data = require 'data/type_mission.php';

        $tms = [];

        for ($a = 2010; $a <= 2099; $a++) {
            foreach ($data as $code => $tm) {
                $tms[] = [
                    'CODE'                     => $code,
                    'LIBELLE'                  => $tm['libelle'],
                    'TAUX_REMU_ID'             => $tm['taux-remu'] ?? null,
                    'TAUX_REMU_MAJORE_ID'      => $tm['taux-remu-majore'] ?? null,
                    'ACCOMPAGNEMENT_ETUDIANTS' => (bool)$tm['accompagnement-etudiants'],
                    'ANNEE_ID'                 => $a,
                    'HISTO_MODIFICATEUR_ID'    => null,
                ];
            }
        }

        return $tms;
    }



    public function TYPE_INDICATEUR()
    {
        $data        = require 'data/indicateurs.php';
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
        $data        = require 'data/indicateurs.php';
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

        $pis = $this->getBdd()->select('SELECT * FROM V_PLAFOND_INDICATEURS');
        foreach ($pis as $pi) {
            $indicateurs[] = $pi;
        }

        return $indicateurs;
    }



    public function WORKFLOW_ETAPE()
    {
        $pdata      = $this->getBdd()->select('SELECT id, code FROM perimetre');
        $perimetres = [];
        foreach ($pdata as $pdatum) {
            $perimetres[$pdatum['CODE']] = (int)$pdatum['ID'];
        }

        $data   = require 'data/workflow_etapes.php';
        $etapes = [];
        $ordre  = 1;
        foreach ($data as $code => $etape) {
            $edata = [
                'ID'                  => $etape['id'],
                'CODE'                => $code,
                'ORDRE'               => $ordre++,
                'PERIMETRE_ID'        => $perimetres[$etape['perimetre']],
                'DESC_NON_FRANCHIE'   => $etape['desc_non_franchie'],
                'DESC_SANS_OBJECTIF'  => $etape['desc_sans_objectif'] ?? null,
                'LIBELLE_AUTRES'      => $etape['libelle_autres'],
                'LIBELLE_INTERVENANT' => $etape['libelle_intervenant'],
                'ROUTE'               => $etape['route'],
                'ROUTE_INTERVENANT'   => $etape['route_intervenant'] ?? null,
            ];
            $etapes[]              = $edata;
        }

        return $etapes;
    }



    public function PARAMETRE()
    {
        $bdd = $this->getBdd();

        $data = require getcwd() . '/data/parametres.php';

        foreach ($data as $nom => $params) {
            if (isset($params['QUERY'])) {
                $query = $params['QUERY'];

                $val = isset($data[$nom]['VALEUR']) ? $data[$nom]['VALEUR'] : null;
                $res = $bdd->selectOne($query, ['valeur' => $val]);
                if (isset($res['VALEUR'])) {
                    $data[$nom]['VALEUR'] = (string)$res['VALEUR'];
                }
                unset($data[$nom]['QUERY']);
            }
        }

        $data['annee']['VALEUR']        = (string)$this->getAnneeCourante();
        $data['annee_import']['VALEUR'] = (string)$this->getAnneeCourante();
        $data['oseuser']['VALEUR']      = (string)$bdd->getHistoUserId();

        $parametres = [];
        foreach ($data as $nom => $params) {
            $params['NOM'] = $nom;
            $parametres[]  = $params;
        }

        return $parametres;
    }

}