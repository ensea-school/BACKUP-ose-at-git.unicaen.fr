<?php

namespace OdfExtractor;

use unicaen\BddAdmin\Bdd;
use Entity\Odf;

class EtapesOdfExtractor
{

    public function run(Bdd $ose, Odf $odf): void
    {
        $console = \OseAdmin::instance()->console();
        $console->println('Insertion des étapes en cours');

        $etapes        = $odf->getEtapes();
        $etapeToInsert = [];
        foreach ($etapes as $etape) {

            $etapeToInsert[] = [
                'LIBELLE'                  => $etape->getLibelle(),
                'Z_STRUCTURE_ID'           => $etape->getStructureId(),
                'Z_TYPE_FORMATION_ID'      => $etape->getTypeFormationId(),
                'ANNEE_DEBUT'              => $etape->getAnneeDebut(),
                'ANNEE_FIN'                => $etape->getAnneeFin(),
                'SOURCE_CODE'              => $etape->getSourceCode(),
                'CODE'                     => $etape->getCode(),
                'Z_DOMAINE_FONCTIONNEL_ID' => $etape->getDomaineFonctionnelId(),
                'NIVEAU'                   => $etape->getNiveau(),
            ];
        }

        $ose->getTable('PEG_ETAPE')->merge($etapeToInsert, ['SOURCE_CODE']);
        $console->println('Les étapes sont désormais présentes dans la table PEG_ETAPE');
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