<?php

namespace Reader;

use Entity\ObjetFormation;
use Entity\VolumeHoraire;
use unicaen\BddAdmin\Bdd;
use Entity\Odf;

class VolumeHoraireReader implements ReaderInterface
{
    public function run(Bdd $pegase, Odf $odf): void
    {
        $console = \OseAdmin::instance()->console();
        $console->println('Récupération des volumes horaires');

        $sql = 'select
                    fe.id_objet_maquette,
                    fe.nombre_theorique_groupe,
                    fe.volume_horaire,
                    vn.code_metier
                FROM schema_odf.formats_enseignement fe
                JOIN schema_odf.nomenclature n ON fe.type_heure = n.code
                JOIN schema_ref.valeurs_nomenclature vn ON n.code = vn.code_metier';

        $volumesHoraires    = $pegase->select($sql, [], ['fetch' => Bdd::FETCH_EACH]);
        $volumeHoraireList = [];
        while ($vh = $volumesHoraires->next()) {
            $newVh = new VolumeHoraire();
            $newVh->setVolumeHoraire($vh['volume_horaire']);
            $newVh->setNombreGroupe($vh['nombre_theorique_groupe']);
            $newVh->setElementId($vh['id_objet_maquette']);
            $newVh->setTypeIntervention($vh['code_metier']);
            $newVh->setSourceCode($vh['id_objet_maquette'].'_'.$vh['code_metier']);
            $volumeHoraireList[$newVh->getSourceCode()] = $newVh;
        }
        $odf->setVolumesHoraires($volumeHoraireList);
        $console->println('Les volumes horaires ont été récupéré');

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


