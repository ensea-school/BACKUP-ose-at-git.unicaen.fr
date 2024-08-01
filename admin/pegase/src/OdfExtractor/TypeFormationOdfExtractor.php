<?php

namespace OdfExtractor;

use unicaen\BddAdmin\Bdd;
use Entity\Odf;

class TypeFormationOdfExtractor
{

    public function run(Bdd $ose, Odf $odf): void
    {
        $console = \OseAdmin::instance()->console();
        $console->println('Insertion des types de formations en cours');
        $typeFormations        = $odf->getTypeFormations();
        $typeFormationToInsert = [];
        foreach ($typeFormations as $typeFormation) {
            $typeFormationToInsert[] = ['LIBELLE_LONG'  => $typeFormation->getLibelleLong(),
                                        'LIBELLE_COURT' => $typeFormation->getLibelleCourt(),
                                        'SOURCE_CODE'   => $typeFormation->getSourceCode(),
            ];
        }

        $ose->getTable('PEG_TYPE_FORMATION')->merge($typeFormationToInsert, ['SOURCE_CODE']);
        $console->println('Les types de formation sont désormais présent dans la table PEG_TYPE_FORMATION');

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