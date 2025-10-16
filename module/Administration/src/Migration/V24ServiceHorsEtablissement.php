<?php

namespace Administration\Migration;

use Unicaen\BddAdmin\Migration\MigrationAction;

class V24ServiceHorsEtablissement extends MigrationAction
{

    public function description(): string
    {
        return "Suppression des etape_id de la table service pour les services hors établissement...";
    }



    public function utile(): bool
    {
        return true;
    }



    public function before()
    {
        $bdd = $this->getBdd();

        $this->logMsg('Suppression des etape_id de la table service pour les services hors établissement...');
        $this->manager()->sauvegarderTable('SERVICE', 'SAVE_SERVICE');
        $bdd->exec('UPDATE service SET etape_id = NULL WHERE element_pedagogique_id IS null AND etape_id IS NOT null ');

    }

}