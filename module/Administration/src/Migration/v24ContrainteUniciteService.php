<?php

namespace Administration\Migration;

use Unicaen\BddAdmin\Migration\MigrationAction;
use Unicaen\BddAdmin\Ddl\Ddl;

class v24ContrainteUniciteService extends MigrationAction
{

    public function description(): string
    {
        return "Suppression de la contrainte d'unicité du Service pour pouvoir la modifier";
    }



    public function utile(): bool
    {
        return $this->manager()->has(Ddl::UNIQUE_CONSTRAINT, 'SERVICE__UN');
    }



    public function before()
    {
        $bdd = $this->getBdd();
        $bdd->exec('ALTER TABLE SERVICE DROP CONSTRAINT SERVICE__UN');
        $bdd->exec('DROP INDEX SERVICE__UN');

    }

}
