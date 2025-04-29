<?php

namespace Chargens\Provider;


use Application\Entity\Db\Annee;
use Application\Model\Chargens\Export;
use Chargens\Entity\Db\Scenario;
use Lieu\Entity\Db\Structure;
use UnicaenApp\View\Model\CsvModel;

class ExportProvider
{
    /**
     * @var ChargensProvider
     */
    private $chargens;

    /**
     * @var array
     */
    private $diff = [];



    /**
     * LienProvider constructor.
     *
     * @param ChargensProvider $chargens
     */
    public function __construct(ChargensProvider $chargens)
    {
        $this->chargens = $chargens;
    }



    public function fromBdd(Annee $annee, Scenario $scenario, ?Structure $structure = null): array
    {
        $sql    = 'SELECT * FROM V_CHARGENS_EXPORT_CSV WHERE scenario_id = :scenario AND annee_id = :annee';
        $params = [
            'scenario' => $scenario->getId(),
            'annee'    => $annee->getId(),
        ];
        if ($structure) {
            $sql                 .= ' AND (structure_porteuse_ids LIKE :structure OR structure_ins_ids LIKE :structure)';
            $params['structure'] = $structure->idsFilter();
        }

        $data = $this->chargens->getEntityManager()->getConnection()->fetchAllAssociative($sql, $params);
        foreach ($data as $i => $d) {
            $l = [
                'annee'                      => $d['ANNEE'],
                'structure-porteuse-code'    => $d['STRUCTURE_PORTEUSE_CODE'],
                'structure-porteuse-libelle' => $d['STRUCTURE_PORTEUSE_LIBELLE'],
                'etape-porteuse-code'        => $d['ETAPE_PORTEUSE_CODE'],
                'etape-porteuse-libelle'     => $d['ETAPE_PORTEUSE_LIBELLE'],

                'structure-ins-code'    => $d['STRUCTURE_INS_CODE'],
                'structure-ins-libelle' => $d['STRUCTURE_INS_LIBELLE'],
                'etape-ins-code'        => $d['ETAPE_INS_CODE'],
                'etape-ins-libelle'     => $d['ETAPE_INS_LIBELLE'],

                'element-code'       => $d['ELEMENT_CODE'],
                'element-libelle'    => $d['ELEMENT_LIBELLE'],
                'element-mutualise'  => $d['ELEMENT_MUTUALISE'],
                'periode'            => $d['PERIODE'],
                'discipline-code'    => $d['DISCIPLINE_CODE'],
                'discipline-libelle' => $d['DISCIPLINE_LIBELLE'],
                'type-heures'        => $d['TYPE_HEURES'],
                'type-intervention'  => $d['TYPE_INTERVENTION'],

                'seuil-ouverture'    => (int)$d['SEUIL_OUVERTURE'],
                'seuil-dedoublement' => (int)$d['SEUIL_DEDOUBLEMENT'],
                'assiduite'          => (float)$d['ASSIDUITE'],
                'effectif-etape'     => (int)$d['EFFECTIF_ETAPE'],
                'effectif-element'   => (int)$d['EFFECTIF_ELEMENT'],
                'heures-ens'         => (float)$d['HEURES_ENS'],
                'groupes'            => (float)$d['GROUPES'],
                'heures'             => (float)$d['HEURES'],
                'hetd'               => (float)$d['HETD'],
            ];

            //if ($l['hetd'] === 120.00) $l['hetd'] = '120,00'; // Hack pour éviter un bug inxplicable
            $data[$i] = $l;
        }

        return $data;
    }



    public function fromCsv(string $filename): array
    {
        $data = [];
        $row  = 1;
        if (!$filename) {
            throw new \Exception('Fichier non fourni');
        }
        if (!file_exists($filename)) {
            throw new \Exception('Fichier inexistant ou inaccessible');
        }
        if (($handle = fopen($filename, "r")) !== false) {
            $head = fgetcsv($handle, 9999, ";");
            if (!$this->checkGoodCSV($head)) {
                throw new \Exception('Le fichier CSV fourni n\'est pas un export des charges d\'enseignement');
            }
            while (($d = fgetcsv($handle, 9999, ";")) !== false) {

                $l      = [
                    'annee'                      => $d[0],
                    'structure-porteuse-code'    => $d[1],
                    'structure-porteuse-libelle' => $d[2],
                    'etape-porteuse-code'        => $d[3],
                    'etape-porteuse-libelle'     => $d[4],

                    'structure-ins-code'    => $d[5],
                    'structure-ins-libelle' => $d[6],
                    'etape-ins-code'        => $d[7],
                    'etape-ins-libelle'     => $d[8],

                    'element-code'       => $d[9],
                    'element-libelle'    => $d[10],
                    'element-mutualise'  => $d[11],
                    'periode'            => $d[12],
                    'discipline-code'    => $d[13],
                    'discipline-libelle' => $d[14],
                    'type-heures'        => $d[15],
                    'type-intervention'  => $d[16],

                    'seuil-ouverture'    => (int)$d[17],
                    'seuil-dedoublement' => (int)$d[18],
                    'assiduite'          => stringToFloat($d[19]),
                    'effectif-etape'     => (int)$d[20],
                    'effectif-element'   => (int)$d[21],
                    'heures-ens'         => stringToFloat($d[22]),
                    'groupes'            => stringToFloat($d[23]),
                    'heures'             => stringToFloat($d[24]),
                    'hetd'               => stringToFloat($d[25]),
                ];
                $data[] = $l;
            }
            fclose($handle);
        }

        return $data;
    }



    protected function checkGoodCSV(array $data)
    {
        return
            (isset($data[25]) && $data[25] === 'HETD')
            && (isset($data[23]) && $data[23] === 'Groupes')
            && (isset($data[1]) && $data[1] === 'Composante porteuse (code)');
    }



    public function toCsv(array $data): CsvModel
    {
        $csvModel = new CsvModel();
        $csvModel->setHeader([
            'annee'                      => 'Année',
            'structure-porteuse-code'    => 'Composante porteuse (code)',
            'structure-porteuse-libelle' => 'Composante porteuse (libellé)',
            'etape-porteuse-code'        => 'Étape porteuse (code)',
            'etape-porteuse-libelle'     => 'Étape porteuse (libellé)',

            'structure-ins-code'    => 'Composante d\'inscription (code)',
            'structure-ins-libelle' => 'Composante d\'inscription (libellé)',
            'etape-ins-code'        => 'Étape d\'inscription (code)',
            'etape-ins-libelle'     => 'Étape d\'inscription (libellé)',

            'element-code'       => 'Ens. (code)',
            'element-libelle'    => 'Enseignement (libellé)',
            'element-mutualise'  => 'Mutualisation',
            'periode'            => 'Période',
            'discipline-code'    => 'Discipline (code)',
            'discipline-libelle' => 'Discipline (libellé)',
            'type-heures'        => 'Régime d\'inscription',
            'type-intervention'  => 'Type d\'intervention',

            'seuil-ouverture'    => 'Seuil d\'ouverture',
            'seuil-dedoublement' => 'Seuil de dédoublement',
            'assiduite'          => 'Assiduité',
            'effectif-etape'     => 'Effectifs (étape)',
            'effectif-element'   => 'Effectifs (élément)',
            'heures-ens'         => 'Vol. Horaire',
            'groupes'            => 'Groupes',
            'heures'             => 'Heures',
            'hetd'               => 'HETD',
        ]);

        foreach ($data as $d) {
            $csvModel->addLine($d);
        }
        $csvModel->setFilename('charges-enseignement.csv');

        return $csvModel;
    }



    public function diff(array $avant, array $apres): array
    {
        $this->diff = [];
        // structure porteuse
        // etape porteuse
        // element
        // structure ins
        // etape ins

        $typesIntervention = $this->chargens->getServiceTypeIntervention()->getList();

        /* On formatte les donées d'avant */
        foreach ($avant as $a) {
            $this->lineToDiff($a, 'avant');
        }

        /* On formatte les donées d'après */
        foreach ($apres as $a) {
            $this->lineToDiff($a, 'apres');
        }

        /* On liste les différences */
        foreach ($this->diff as $k1 => $d1) {
            foreach ($d1['etapes'] as $k2 => $d2) {
                foreach ($d2['elements'] as $ke => $d) {
                    $rd = [];
                    if (!array_key_exists('avant', $d)) {
                        $rd = 'new';
                    } elseif (!array_key_exists('apres', $d)) {
                        $rd = 'old';
                    } else {
                        $typeHeures = array_keys($d['avant']['effectifs']);

                        foreach ($typeHeures as $typeHeure) {
                            $this->diff[$k1]['etapes'][$k2]['elements'][$ke]['avant']['effectifs'][$typeHeure] =
                                $d['avant']['effectifs'][$typeHeure] / count($d['avant']['ti']);

                            $this->diff[$k1]['etapes'][$k2]['elements'][$ke]['apres']['effectifs'][$typeHeure] =
                                $d['apres']['effectifs'][$typeHeure] / count($d['apres']['ti']);

                            if ($d['avant']['effectifs'][$typeHeure] != $d['apres']['effectifs'][$typeHeure]) {
                                $rd['effectifs'][$typeHeure] = true;
                            }
                        }

                        foreach ($typesIntervention as $typeIntervention) {
                            $ti = $typeIntervention->getCode();

                            $hasAvant = isset($d['avant']['ti'][$ti]);
                            $hasApres = isset($d['apres']['ti'][$ti]);

                            if ($hasAvant && !$hasApres) {
                                $rd['ti'][$ti] = 'old';
                            } elseif (!$hasAvant && $hasApres) {
                                $rd['ti'][$ti] = 'new';
                            } elseif ($hasAvant && $hasApres) {
                                $rdti = [];
                                $keys = array_keys($d['avant']['ti'][$ti]);
                                foreach ($keys as $k) {
                                    if ($d['avant']['ti'][$ti][$k] != $d['apres']['ti'][$ti][$k]) {
                                        $rdti[$k] = true;
                                    }
                                }
                                if (!empty($rdti)) {
                                    $rd['ti'][$ti] = $rdti;
                                }
                            }
                        }
                    }

                    $this->diff[$k1]['etapes'][$k2]['elements'][$ke]['diff'] = $rd;
                }
            }
        }

        /* On retire tout ce qui est identique */
        foreach ($this->diff as $k1 => $d1) {
            foreach ($d1['etapes'] as $k2 => $d2) {
                foreach ($d2['elements'] as $ke => $d) {
                    if (empty($d['diff'])) {
                        unset($this->diff[$k1]['etapes'][$k2]['elements'][$ke]);
                    }
//                    if ($ke != 'act_3925') { // TEST TEST TEST TEST TEST TEST TEST
//                        unset($this->diff[$k1]['etapes'][$k2]['elements'][$ke]);
//                    }
                }
                if (empty($this->diff[$k1]['etapes'][$k2]['elements'])) {
                    unset($this->diff[$k1]['etapes'][$k2]);
                }
            }
            if (empty($this->diff[$k1]['etapes'])) {
                unset($this->diff[$k1]);
            }
        }

        return $this->diff;
    }



    protected function lineToDiff(array $a, string $avap)
    {
        $spc = $a['structure-porteuse-code'];
        $epc = $a['etape-porteuse-code'];
        $ec  = $a['element-code'];
        $th  = strtolower($a['type-heures']);
        $ti  = $a['type-intervention'];
        if (!array_key_exists($spc, $this->diff)) {
            $this->diff[$spc] = [
                'libelle' => $a['structure-porteuse-libelle'],
                'avant'   => [
                    'heures' => 0,
                    'hetd'   => 0,
                ],
                'apres'   => [
                    'heures' => 0,
                    'hetd'   => 0,
                ],
                'etapes'  => [],
            ];
        }
        if (!array_key_exists($epc, $this->diff[$spc]['etapes'])) {
            $this->diff[$spc]['etapes'][$epc] = [
                'code'     => $a['etape-porteuse-code'],
                'libelle'  => $a['etape-porteuse-libelle'],
                'avant'    => [
                    'heures' => 0,
                    'hetd'   => 0,
                ],
                'apres'    => [
                    'heures' => 0,
                    'hetd'   => 0,
                ],
                'elements' => [],
            ];
        }
        if (!isset($this->diff[$spc]['etapes'][$epc]['elements'][$ec])) {
            $this->diff[$spc]['etapes'][$epc]['elements'][$ec] = [
                'code'               => $ec,
                'libelle'            => $a['element-libelle'],
                'periode'            => $a['periode'],
                'discipline-code'    => $a['discipline-code'],
                'discipline-libelle' => $a['discipline-libelle'],
            ];
        }
        if (!isset($this->diff[$spc]['etapes'][$epc]['elements'][$ec][$avap])) {
            $this->diff[$spc]['etapes'][$epc]['elements'][$ec][$avap] = [
                'effectifs' => ['fi' => 0, 'fa' => 0, 'fc' => 0],
                'ti'        => [],
            ];
        }
        if (!isset($this->diff[$spc]['etapes'][$epc]['elements'][$ec][$avap]['ti'][$ti])) {
            $this->diff[$spc]['etapes'][$epc]['elements'][$ec][$avap]['ti'][$ti] = [
                'seuil-ouverture'    => $a['seuil-ouverture'],
                'seuil-dedoublement' => $a['seuil-dedoublement'],
                'assiduite'          => $a['assiduite'],
                'heures-ens'         => $a['heures-ens'],
                'groupes'            => 0,
                'heures'             => 0,
                'hetd'               => 0,
            ];
        }

        if ($a['etape-porteuse-code'] == $a['etape-ins-code']) {
            // Si on est sur l'étape porteue, elors on en force les paramètres
            $this->diff[$spc]['etapes'][$epc]['elements'][$ec][$avap]['ti'][$ti]['seuil-ouverture']    = $a['seuil-ouverture'];
            $this->diff[$spc]['etapes'][$epc]['elements'][$ec][$avap]['ti'][$ti]['seuil-dedoublement'] = $a['seuil-dedoublement'];
        }

        $this->diff[$spc]['etapes'][$epc]['elements'][$ec][$avap]['effectifs'][$th]     += $a['effectif-element'];
        $this->diff[$spc]['etapes'][$epc]['elements'][$ec][$avap]['ti'][$ti]['groupes'] += $a['groupes'];
        $this->diff[$spc]['etapes'][$epc]['elements'][$ec][$avap]['ti'][$ti]['heures']  += $a['heures'];
        $this->diff[$spc]['etapes'][$epc]['elements'][$ec][$avap]['ti'][$ti]['hetd']    += $a['hetd'];

        $this->diff[$spc][$avap]['heures'] += $a['heures'];
        $this->diff[$spc][$avap]['hetd']   += $a['hetd'];

        $this->diff[$spc]['etapes'][$epc][$avap]['heures'] += $a['heures'];
        $this->diff[$spc]['etapes'][$epc][$avap]['hetd']   += $a['hetd'];
    }



    /**
     * This method is called by var_dump() when dumping an object to get the properties that should be shown.
     * If the method isn't defined on an object, then all public, protected and private properties will be shown.
     *
     * @return array
     * @since PHP 5.6.0
     *
     * @link  http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.debuginfo
     */
    function __debugInfo()
    {
        return [];
    }

}