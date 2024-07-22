<?php

namespace Reader;

use Entity\ObjetFormation;
use unicaen\BddAdmin\Bdd;
use Entity\Odf;

class ObjetFormationReader implements ReaderInterface
{
    public function run(Bdd $pegase, Odf $odf): void
    {
        $console = \OseAdmin::instance()->console();
        $console->println('Récupération de l\'arborescence en cours');

        $sql = 'select
                    e.id,
                    om.id,
                    e.id_objet_maquette_parent,
                    om.libelle_long,
                    om.code_structure,       
                    om.code,       
                    om.temoin_tele_enseignement,      
                    esp.annee_universitaire as annee_universitaire,
                    esp.date_debut_validite as date_debut_validite,
                    esp.date_fin_validite as date_fin_validite
                FROM schema_odf.objet_maquette om
                JOIN schema_odf.enfant e ON e.id_objet_maquette = om.id
                LEFT JOIN schema_odf.espace esp on esp.id = om.id_espace';

        $arborescence    = $pegase->select($sql, [], ['fetch' => Bdd::FETCH_EACH]);
        $enfants         = [];
        $objetsFormation = [];
        while ($element = $arborescence->next()) {
            $enfants[$element['id_objet_maquette_parent']][$element['id']] = $element['id'];

            if (!isset($objetsFormation[$element['id']])) {
                $objetFormation = new ObjetFormation();
                $objetFormation->setLibelle($element['libelle_long']);
                $objetFormation->setStructureId($element['code_structure']);
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


