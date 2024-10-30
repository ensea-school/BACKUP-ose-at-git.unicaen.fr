<?php

namespace Administration\Migration;

use Unicaen\BddAdmin\Migration\MigrationAction;

class v23minimum extends MigrationAction
{

    public function description(): string
    {
        return "Détection de la version 23 au minimum";
    }



    public function utile(): bool
    {
        return !$this->manager()->hasColumn('STRUCTURE', 'IDS') // on est en v23
            && $this->manager()->hasTable('STRUCTURE'); // pour éviter de se taper le blocage en cas de nouvelle install
    }



    public function before()
    {
        $this->logError('Vous devez devez d\'abord migrer en version 23 avant de monter en version ultérieure.');
    }

}