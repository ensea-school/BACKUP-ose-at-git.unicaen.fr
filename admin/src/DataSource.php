<?php





class DataSource
{
    private OseAdmin $oseAdmin;



    /**
     * @param OseAdmin $oseAdmin
     */
    public function __construct(OseAdmin $oseAdmin)
    {
        $this->oseAdmin = $oseAdmin;
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
        $data = require $this->oseAdmin->getOseDir() . 'data/formules.php';;
        $formules = [];
        foreach ($data as $id => $formule) {
            $formule['ID'] = $id;
            if (!isset($formule['ACTIVE'])) $formule['ACTIVE'] = true;
            for ($i = 1; $i < 6; $i++) {
                if (!isset($formule['I_PARAM_' . $i . '_LIBELLE'])) $formule['I_PARAM_' . $i . '_LIBELLE'] = null;
                if (!isset($formule['VH_PARAM_' . $i . '_LIBELLE'])) $formule['VH_PARAM_' . $i . '_LIBELLE'] = null;
            }
            $formules[] = $formule;
        }

        return $formules;
    }



    public function STATUT()
    {
        $data = $this->donneesDefaut['STATUT'];

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



    public function TYPE_PIECE_JOINTE_STATUT()
    {
        $data = $this->donneesDefaut['TYPE_PIECE_JOINTE_STATUT'];

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



    public function TAUX_REMU()
    {
        $data = require $this->oseAdmin->getOseDir() . 'data/taux_remu.php';

        $tauxRemu = [];

        foreach ($data as $code => $taux) {
            $taux       = [
                'CODE'    => $code,
                'LIBELLE' => $taux['libelle'],

            ];
            $tauxRemu[] = $taux;
        }

        return $tauxRemu;
    }



    public function TAUX_REMU_VALEUR()
    {
        $data = require $this->oseAdmin->getOseDir() . 'data/taux_remu.php';

        $tauxValeurs = [];

        foreach ($data as $code => $taux) {
            foreach ($taux['valeurs'] as $dateEffet => $valeur) {
                $tauxValeur    = [
                    'TAUX_REMU_ID' => $code,
                    'DATE_EFFET'   => \DateTime::createFromFormat('d/m/Y', $dateEffet),
                    'VALEUR'       => $valeur,
                ];
                $tauxValeurs[] = $tauxValeur;
            }
        }

        return $tauxValeurs;
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