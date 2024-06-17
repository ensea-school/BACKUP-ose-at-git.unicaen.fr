<?php

namespace OdfExtractor;

use unicaen\BddAdmin\Bdd;
use Entity\Odf;

class StructuresOdfExtractor
{

    public function run(Bdd $ose, Odf $odf): void
    {
        $console = \OseAdmin::instance()->console();
        $console->println('Insertion des structures en cours');

        $structures        = $odf->getStructures();
        $structuresToInsert = [];
        foreach ($structures as $structure) {

            $structuresToInsert[] = [
                'CODE'                => $structure->getCode(),
                'LIBELLE_OFFICIELLE'  => $structure->getLibelleOfficielle(),
                'LIBELLE_PRINCIPALE'  => $structure->getLibellePrincipale(),
                'DATE_DEBUT_VALIDITE' => $structure->getDateDebut(),
                'DATE_FIN_VALIDITE'   => $structure->getDateFin(),
                'SOURCE_CODE'         => $structure->getCode(),
            ];
        }

        $ose->getTable('PEG_STRUCTURE')->merge($structuresToInsert, ['SOURCE_CODE']);
        $console->println('Les structures sont désormais présentes dans la table PEG_STRUCTURE');

    }



    public function versionMin(): float
    {
        return 24.0;
    }



    public function versionMax(): float
    {
        // TODO: Implement versionMax() method.
        return 99.0;
    }
}