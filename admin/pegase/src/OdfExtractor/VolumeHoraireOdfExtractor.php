<?php

namespace OdfExtractor;

use Entity\VolumeHoraire;
use unicaen\BddAdmin\Bdd;
use Entity\Odf;

class VolumeHoraireOdfExtractor
{

    public function run(Bdd $ose, Odf $odf): void
    {
        $console = \OseAdmin::instance()->console();
        $console->println('Insertion des volumes horaires en cours');
        $volumesHoraires       = $odf->getVolumesHoraires();
        $volumeHoraireToInsert = [];
        foreach ($volumesHoraires as $volumeHoraire) {
            /** @var VolumeHoraire $volumeHoraire */
            $volumeHoraireToInsert[] = [
                'Z_TYPE_INTERVENTION_ID'   => $volumeHoraire->getTypeIntervention(),
                'HEURES'                   => $volumeHoraire->getVolumeHoraire(),
                'SOURCE_CODE'              => $volumeHoraire->getSourceCode(),
                'Z_ELEMENT_PEDAGOGIQUE_ID' => $volumeHoraire->getElementId(),
                'GROUPES'                  => $volumeHoraire->getNombreGroupe(),
                'ANNEE_DEBUT'              => $volumeHoraire->getAnneeDebut(),
                'ANNEE_FIN'                => $volumeHoraire->getAnneeFin(),
            ];
        }

        $ose->getTable('PEG_VOLUME_HORAIRE')->merge($volumeHoraireToInsert, ['SOURCE_CODE']);
        $console->println('Les volumes horaires sont désormais présent dans la table PEG_VOLUME_HORAIRE');
    }



    public function versionMin(): float
    {
        // TODO: Implement versionMin() method.
        return 0.0;
    }



    public function versionMax(): float
    {
        // TODO: Implement versionMax() method.
        return 99.0;
    }
}