<?php

namespace Reader;

use Entity\Odf;
use Entity\TypeFormation;
use Unicaen\BddAdmin\Bdd;

class TypeFormationReader implements ReaderInterface
{
    public function run(Bdd $pegase, Odf $odf): void
    {
        $console = \OseAdmin::instance()->console();
        $console->println('Récupération des types de formations en cours');
        $listTypeFo = [];

        $sql = 'select
                d.code,
                d.libelle_long,
                d.libelle_court,
                d.date_debut_validite,
                d.date_fin_validite
                FROM schema_ref.type_diplome d';

        $res = $pegase->select($sql, [], ['fetch' => Bdd::FETCH_EACH]);

        while ($typeFormation = $res->next()) {
            $typFo = new TypeFormation($odf);
            $typFo->setLibelleCourt($typeFormation['libelle_court']);
            $typFo->setLibelleLong($typeFormation['libelle_long']);
            $typFo->setSourceCode($typeFormation['code']);
            $typFo->setDateDebut($typeFormation['date_debut_validite']);
            $typFo->setDateFin($typeFormation['date_fin_validite']);
            $listTypeFo[$typFo->getSourceCode()] = $typFo;
        }
        $odf->setTypeFormations($listTypeFo);

        $console->println('Les types de formations ont été récupéré');

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