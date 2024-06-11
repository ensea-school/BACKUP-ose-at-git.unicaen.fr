<?php


namespace Adapter;

use Entity\Odf;

class TypeFormationAdapter implements DataAdapterInterface
{
    public function run(Odf $odf): void
    {
        $console = \OseAdmin::instance()->console();
        $console->println('Traitement des types de formation récupérés');

        $typeFormations = $odf->getTypeFormations();
        $typePeriodeRes = [];
        foreach ($typeFormations as $typeFormation) {
            $periodesAnnee = $odf->traitementPeriode(null, $typeFormation->getDateDebut(), $typeFormation->getDateFin());
            $typeFormation->setAnneeDebut($periodesAnnee['anneeDebut']);
            $typeFormation->setAnneeFin($periodesAnnee['anneeFin']);

            $typePeriodeRes[] = $typeFormation;
        }
        $odf->setTypeFormations($typePeriodeRes);

        $console->println('Fin du traitement des types de formation récupérés');

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