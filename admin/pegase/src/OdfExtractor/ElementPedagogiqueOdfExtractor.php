<?php

namespace OdfExtractor;

use Entity\ElementPedagogique;
use unicaen\BddAdmin\Bdd;
use Entity\Odf;

class ElementPedagogiqueOdfExtractor
{

    public function run(Bdd $ose, Odf $odf): void
    {
        $console = \OseAdmin::instance()->console();
        $console->println('Insertion des éléments pédagogiques en cours');

        $elements         = $odf->getElementsPedagogiques();
        $elementsToInsert = [];
        foreach ($elements as $element) {
            /** @var ElementPedagogique $element */
            $elementsToInsert[] = [
                'LIBELLE'         => $element->getLibelle(),
                'Z_STRUCTURE_ID'  => $element->getStructureId(),
                'ANNEE_DEBUT'     => $element->getAnneeDebut(),
                'ANNEE_FIN'       => $element->getAnneeFin(),
                'SOURCE_CODE'     => $element->getSourceCode(),
                'CODE'            => $element->getCode(),
                'FI'              => 1,
                'FA'              => 1,
                'FC'              => 1,
                'Z_DISCIPLINE_ID' => null,
                'Z_PERIODE_ID'    => null,
                'Z_ETAPE_ID'      => $element->getEtapeId(),
                'TAUX_FOAD'       => $element->getTauxFoad(),

            ];
        }

        $ose->getTable('PEG_ELEMENT_PEDAGOGIQUE')->merge($elementsToInsert, ['SOURCE_CODE']);
        $console->println('Les éléments pédagogiques sont désormais présentes dans la table PEG_ELEMENT_PEDAGOGIQUE');
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