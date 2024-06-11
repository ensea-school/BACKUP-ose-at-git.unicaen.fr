<?php


namespace Adapter;

use Entity\Odf;

class StructureAdapter implements DataAdapterInterface
{
    public function run(Odf $odf): void
    {
        $console = \OseAdmin::instance()->console();
        $console->println('Traitement des structures récupérées');


        $console->println('Fin du traitement des structures récupérées');


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