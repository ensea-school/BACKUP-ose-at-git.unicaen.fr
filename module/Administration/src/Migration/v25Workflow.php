<?php

namespace Administration\Migration;

use Unicaen\BddAdmin\Migration\MigrationAction;

class v25Workflow extends MigrationAction
{


    public function description(): string
    {
        return "Suppression de doublons dans les validations";
    }



    public function utile(): bool
    {
        return $this->manager()->hasNew('table', 'WORKFLOW_ETAPE');
    }



    public function before()
    {
        $this->getBdd()->exec('DROP TABLE TBL_WORKFLOW');
        $this->getBdd()->exec('DROP TABLE TBL_VALIDATION_ENSEIGNEMENT');
        $this->getBdd()->exec('DROP TABLE TBL_VALIDATION_REFERRENTIEL');
    }

}
