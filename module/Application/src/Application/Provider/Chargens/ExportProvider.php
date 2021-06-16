<?php

namespace Application\Provider\Chargens;


use Application\Model\Chargens\Export;
use Application\Entity\Db\Annee;
use Application\Entity\Db\Scenario;
use Application\Entity\Db\Structure;
use UnicaenApp\View\Model\CsvModel;

class ExportProvider
{
    /**
     * @var ChargensProvider
     */
    private $chargens;



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
            $sql                 .= ' AND (structure_porteuse_id = :structure OR structure_ins_id = :structure)';
            $params['structure'] = $structure->getId();
        }

        $data = $this->chargens->getBdd()->fetch($sql, $params);
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

            if ($l['hetd'] === 120.00) $l['hetd'] = '120,00'; // Hack pour éviter un bug inxplicable
            $data[$i] = $l;
        }

        return $data;
    }



    public function dataFromFile(string $filename): array
    {

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