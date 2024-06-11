<?php


namespace Adapter;

use Entity\NoeudOld;
use Entity\Odf;

class NoeudAdapter implements DataAdapterInterface
{
    /**
     * @param Odf $odf
     *
     * @return void
     */
    public function run(Odf $odf): void
    {
        foreach ($odf->getObjetsFormation() as $objetFormation) {
            $noeud = new NoeudOld($odf);
            $noeud->setZId($objetFormation->getId());
            $noeud->setCode($objetFormation->getCode());
            $noeud->setSourceCode($objetFormation->getCode());
            $noeud->setListe($objetFormation->getCodeCategorie() == 'GRP');

            //TODO traitement de la periode
            $anneeDebut = 2000;
            $anneeFin   = 2000;
            $noeud->setAnneeDebut($anneeDebut);
            $noeud->setAnneeFin($anneeFin);

            $noeud->setLibelle($objetFormation->getLibelleLong());
//            $noeud->setStructureId($objetFormation->get

//            private string $zEtapeId;
//
//    private string $zElementPedagogiqueId;
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