<?php

namespace Administration\Migration;

use Unicaen\BddAdmin\Migration\MigrationAction;

class v24Contrats extends MigrationAction
{


    public function description(): string
    {
        return "Vidage du tableau de bord des contrats pour permettre sa migration";
    }



    public function utile(): bool
    {
        return $this->manager()->hasNewColumn('TBL_CONTRAT', 'AUTRES_LIBELLES');
    }



    public function before()
    {
        $sql = "DELETE FROM TBL_CONTRAT";
        $this->getBdd()->exec($sql);
    }
}