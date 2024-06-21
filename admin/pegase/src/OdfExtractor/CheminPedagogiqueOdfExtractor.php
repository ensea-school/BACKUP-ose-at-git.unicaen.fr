<?php

namespace OdfExtractor;

use Entity\CheminPedagogique;
use unicaen\BddAdmin\Bdd;
use Entity\Odf;

class CheminPedagogiqueOdfExtractor
{

    public function run(Bdd $ose, Odf $odf): void
    {
        $console = \OseAdmin::instance()->console();
        $console->println('Insertion des chemins pédagogiques en cours');
        $chemins         = $odf->getCheminsPedagogiques();
        $cheminsToInsert = [];
        foreach ($chemins as $chemin) {
            /** @var CheminPedagogique $chemin */
            $cheminsToInsert[] = [
                'Z_ETAPE_ID'                 => $chemin->getEtapeId(),
                'Z_ETAPE_CODE'               => $chemin->getEtapeCode(),
                'Z_ELEMENT_PEDAGOGIQUE_ID'   => $chemin->getElementPedagogiqueId(),
                'Z_ELEMENT_PEDAGOGIQUE_CODE' => $chemin->getElementPedagogiqueCode(),
                'SOURCE_CODE'                => $chemin->getId(),
                'ANNEE_DEBUT'                => $chemin->getAnneeDebut(),
                'ANNEE_FIN'                => $chemin->getAnneeFin(),
            ];
        }
        $ose->getTable('PEG_CHEMIN_PEDAGOGIQUE')->merge($cheminsToInsert, ['SOURCE_CODE']);
        $console->println('Les chemins pédagogiques sont désormais présentes dans la table PEG_CHEMIN_PEDAGOGIQUE');
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