<?php

namespace Reader;

use Entity\Odf;
use Entity\Structure;
use Unicaen\BddAdmin\Bdd;

class StructureReader implements ReaderInterface
{
    public function run(Bdd $pegase, Odf $odf): void
    {
        $console = \OseAdmin::instance()->console();
        $console->println('Récupération des structures en cours');

        //TODO ajouter adresse dans la requete
        $sql = 'select
                S.code,
                s.denomination_principale,
                s.appellation_officielle,
                s.date_debut_validite,
                s.date_fin_validite,
                s.temoin_visible
                FROM schema_ref.structure s';


        $res            = $pegase->select($sql, [], ['fetch' => Bdd::FETCH_EACH]);
        $listStructures = [];
        while ($structure = $res->next()) {
            if (!isset($listStructures[$structure['code']])) {
                $newStruct = new Structure();
                $newStruct->setCode($structure['code']);
                $newStruct->setLibellePrincipale($structure['denomination_principale']);
                $newStruct->setLibelleOfficielle($structure['appellation_officielle']);
                $newStruct->setDateDebut($structure['date_debut_validite']);
                $newStruct->setDateFin($structure['date_fin_validite']);
                $newStruct->setTemoinVisible($structure['temoin_visible']);
                $listStructures[$newStruct->getCode()] = $newStruct;
            }
        }
        $odf->setStructures($listStructures);
        $console->println('Les structures ont été récupérés');

    }



    public function versionMin(): float
    {
        // TODO: Implement versionMin() method.
        return 24.0;
    }



    public function versionMax(): float
    {
        // TODO: Implement versionMax() method.
        return 24.0;
    }

}