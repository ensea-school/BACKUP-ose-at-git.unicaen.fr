<?php


namespace Adapter;

use Entity\NoeudOld;
use Entity\Odf;
use Unicaen\BddAdmin\Bdd;

class LienAdapter implements DataAdapterInterface
{
    /**
     * @param Odf $odf
     *
     * @return void
     */
    public function run(Odf $odf, Bdd $pegase = null): void
    {
        foreach ($odf->getMaquettes() as $maquette) {

          //Créer les liens


        }
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