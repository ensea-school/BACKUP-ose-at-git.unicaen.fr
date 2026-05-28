<?php

namespace Administration\Migration;

use Unicaen\BddAdmin\Migration\MigrationAction;

class v24ContrainteUniciteService extends MigrationAction
{

    public function description(): string
    {
        return "Suppression de la contrainte d'unicité du Service pour pouvoir la modifier";
    }



    public function utile(): bool
    {
        return true;
    }



    public function before()
    {
        $bdd = $this->getBdd();
        $bdd->exec('ALTER TABLE SERVICE DROP CONSTRAINT SERVICE__UN');
        $bdd->exec('DROP INDEX SERVICE__UN');

    }

}