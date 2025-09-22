<?php

namespace Administration\DataSource;

use Application\Entity\Db\Annee;
use Application\Entity\Db\Perimetre;
use Unicaen\BddAdmin\BddAwareTrait;
use Workflow\Entity\Db\WorkflowEtapeDependance;

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



    public function ANNEE(): array
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



    public function DEPARTEMENT(): array
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



    public function IMPORT_TABLES(): array
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



    public function JOUR_FERIE(): array
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



    public function ETAT_SORTIE(): array
    {
        return require 'data/etats_sortie.php';
    }



    public function CATEGORIE_PRIVILEGE(): array
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



    public function PRIVILEGE(): array
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



    public function STATUT(): array
    {
        $donneesParDefaut = require 'data/donnees_par_defaut.php';
        $data             = $donneesParDefaut['STATUT'];

        $statuts = [];
        for ($a = Annee::MIN_DATA; $a <= Annee::MAX; $a++) {
            foreach ($data as $d) {
                $d['ANNEE_ID']              = $a;
                $d['HISTO_MODIFICATEUR_ID'] = null;
                $statuts[]                  = $d;
            }
        }

        return $statuts;
    }



    public function FONCTION_REFERENTIEL(): array
    {
        $donneesParDefaut = require 'data/donnees_par_defaut.php';
        $data             = $donneesParDefaut['FONCTION_REFERENTIEL'];

        $fonctions = [];
        for ($a = Annee::MIN_DATA; $a <= Annee::MAX; $a++) {
            foreach ($data as $d) {
                $d['ANNEE_ID']              = $a;
                $d['HISTO_MODIFICATEUR_ID'] = null;
                $fonctions[]                = $d;
            }
        }

        return $fonctions;
    }



    public function TYPE_PIECE_JOINTE_STATUT(): array
    {
        $donneesParDefaut = require 'data/donnees_par_defaut.php';
        $data             = $donneesParDefaut['TYPE_PIECE_JOINTE_STATUT'];

        $statuts = [];
        for ($a = Annee::MIN_DATA; $a <= Annee::MAX; $a++) {
            foreach ($data as $d) {
                $d['ANNEE_ID']              = $a;
                $d['HISTO_MODIFICATEUR_ID'] = null;
                $statuts[]                  = $d;
            }
        }

        return $statuts;
    }



    public function PLAFOND(): array
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



    public function PLAFOND_ETAT(): array
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



    public function PLAFOND_PERIMETRE(): array
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



    public function TAUX_REMU(string $action): array
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



    public function TAUX_REMU_VALEUR(string $action): array
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



    public function TYPE_MISSION(): array
    {
        $data = require 'data/type_mission.php';

        $tms = [];

        for ($a = Annee::MIN_DATA; $a <= Annee::MAX; $a++) {
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



    public function TYPE_INDICATEUR(): array
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



    public function INDICATEUR(): array
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



    public function WORKFLOW_ETAPE(): array
    {
        $data   = require 'data/workflow_etapes.php';
        $etapes = [];
        $ordre  = 1;
        foreach ($data as $code => $etape) {
            $edata = [
                'code'                  => $code,
                'perimetre_id'          => $etape['perimetre'],
                'route'                 => $etape['route'],
                'route_intervenant'     => $etape['route_intervenant'] ?? null,
                'libelle_intervenant'   => $etape['libelle_intervenant'],
                'libelle_autres'        => $etape['libelle_autres'],
                'desc_non_franchie'     => $etape['desc_non_franchie'],
                'desc_sans_objectif'    => $etape['desc_sans_objectif'] ?? null,
                'ordre'                 => $ordre++,
                'histo_modificateur_id' => null,
            ];

            for ($a = Annee::MIN_DATA; $a <= Annee::MAX; $a++) {
                $edata['annee_id'] = $a;
                mpg_upper($edata);
                $etapes[] = $edata;
            }
        }

        return $etapes;
    }



    public function WORKFLOW_ETAPE_DEPENDANCE(): array
    {
        $data = require 'data/workflow_etapes.php';

        $deps = [];
        foreach ($data as $etapeSuivCode => $etape) {
            if (isset($etape['dependances'])) {
                foreach ($etape['dependances'] as $etapePrecCode => $dependance) {
                    $dep    = [
                        'etape_suivante_id'     => $etapeSuivCode,
                        'etape_precedante_id'   => $etapePrecCode,
                        'active'                => true,
                        'type_intervenant_id'   => $dependance['type_intervenant'] ?? null,
                        'perimetre_id'          => $dependance['perimetre'] ?? Perimetre::ETABLISSEMENT,
                        'avancement'            => $dependance['avancement'] ?? WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT,
                        'histo_modificateur_id' => null,
                    ];

                    for ($a = Annee::MIN_DATA; $a <= Annee::MAX; $a++) {
                        $ndep = $dep;
                        $ndep['etape_suivante_id'] .= '-'.(string)$a;
                        $ndep['etape_precedante_id'] .= '-'.(string)$a;
                        $ndep['annee_id'] = $a;
                        mpg_upper($ndep);
                        $deps[] = $ndep;
                    }
                }
            }
        }

        return $deps;
    }



    public function PARAMETRE(): array
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