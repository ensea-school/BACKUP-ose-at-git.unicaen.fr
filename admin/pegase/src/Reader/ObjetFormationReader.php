<?php

namespace Reader;

use Entity\ObjetFormation;
use OseAdmin;
use unicaen\BddAdmin\Bdd;
use Entity\Odf;

class ObjetFormationReader implements ReaderInterface
{
    public function run(Bdd $pegase, Odf $odf): void
    {
        $console = \OseAdmin::instance()->console();
        $console->println('Récupération de l\'arborescence en cours');

        $config = OseAdmin::instance()->config()->get('pegase');

        if (isset($config['codes_type_objet_formation_ignores'])) {
            $codes_type_objet_formation_ignores = $config['codes_type_objet_formation_ignores'];
        } else {
            $codes_type_objet_formation_ignores = null;
        }
        $param = [];

        $sql = 'select
                    e.id,
                    om.id,
                    e.id_objet_maquette_parent,
                    om.libelle_long,
                    om.code_structure,     
                    om.structures_porteuse,  
                    om.code,       
                    om.temoin_tele_enseignement,      
                    esp.annee_universitaire as annee_universitaire,
                    esp.date_debut_validite as date_debut_validite,
                    esp.date_fin_validite as date_fin_validite,
                    om.type_objet_maquette
                FROM schema_odf.objet_maquette om
                JOIN schema_odf.enfant e ON e.id_objet_maquette = om.id
                LEFT JOIN schema_odf.espace esp on esp.id = om.id_espace';

        if (!empty($codes_type_objet_formation_ignores)) {
            $sql .= ' WHERE om.code_type_objet_formation NOT IN (';
            $i=0;
            foreach ($codes_type_objet_formation_ignores as $code_type_objet_formation_ignore) {
                $nameCode = 'code_type_objet_formation_ignores_' . $i;
                if($i != 0){
                    $sql .= ', ';
                }
                    $sql .= ':' . $nameCode;
                $param[$nameCode] = $code_type_objet_formation_ignore;
                $i++;
            }
            $sql .= ')';
        }
        $arborescence    = $pegase->select($sql, $param, ['fetch' => Bdd::FETCH_EACH]);
        $enfants         = [];
        $objetsFormation = [];
        while ($element = $arborescence->next()) {
            $enfants[$element['id_objet_maquette_parent']][$element['id']] = $element['id'];

            if (!isset($objetsFormation[$element['id']])) {
                $objetFormation = new ObjetFormation();
                $objetFormation->setLibelle($element['libelle_long']);
                if ($element['structures_porteuse'] != null) {
                    $objetFormation->setStructureId($element['structures_porteuse']);
                } else {
                    $objetFormation->setStructureId($element['code_structure']);
                }

                $objetFormation->setAnneeUniversitaire($element['annee_universitaire']);
                $objetFormation->setDateDebut($element['date_debut_validite']);
                $objetFormation->setDateFin($element['date_fin_validite']);
                $objetFormation->setSourceCode($element['id']);
                $objetFormation->setCode($element['code']);
                $objetFormation->setTauxFoad($element['temoin_tele_enseignement']);
                $objetsFormation[$objetFormation->getSourceCode()] = $objetFormation;
            }
        }
        $odf->setEnfants($enfants);
        $odf->setObjetsFormation($objetsFormation);
        $console->println('L\'arborescence a été récupéré');

    }



    public function versionMin(): float
    {
        // TODO: Implement versionMin() method.
        return 24.0;
    }



    public function versionMax(): float
    {
        // TODO: Implement versionMax() method.
        return 24.0;
    }

}


