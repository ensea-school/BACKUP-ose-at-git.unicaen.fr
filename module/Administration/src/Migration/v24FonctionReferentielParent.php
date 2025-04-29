<?php

namespace Administration\Migration;

use Unicaen\BddAdmin\Migration\MigrationAction;

class v24FonctionReferentielParent extends MigrationAction
{


    public function description(): string
    {
        return "Corrige les fonctions référentiels ayant un problème de fonction parent";
    }



    public function utile(): bool
    {
        $sql   = "select * from fonction_referentiel fr 
                  JOIN fonction_referentiel frp ON frp.id = fr.parent_id
                  where fr.annee_id <> frp.annee_id";
        $param = $this->getBdd()->select($sql);
        if (empty($param)) {
            return false;
        } else {
            return true;
        }
    }



    public function before()
    {
        $this->logMsg("Corrections des parents des fonction référentiels");
        $sql         = "select distinct fr.id as id, fr.code as code, frp.code as code_parent, fr.annee_id, frpn.id as new_parent_id from fonction_referentiel fr 
                JOIN fonction_referentiel frp ON frp.id = fr.parent_id
                JOIN fonction_referentiel frpn ON frpn.annee_id = fr.annee_id AND frpn.code = frp.code
                where fr.annee_id <> frp.annee_id";
        $selectQuery = $this->getBdd()->selectEach($sql);
        while ($fonction = $selectQuery->next()) {
            $param = ['new_parent_id' => $fonction['NEW_PARENT_ID'], 'fr_id' => $fonction['ID']];
            $this->getBdd()->exec('UPDATE fonction_referentiel fr
                                SET fr.parent_id = :new_parent_id
                                WHERE fr.id = :fr_id', $param);

        }

    }
}