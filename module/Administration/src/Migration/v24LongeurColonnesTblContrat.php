<?php

namespace Administration\Migration;

use Unicaen\BddAdmin\Migration\MigrationAction;

class v24LongeurColonnesTblContrat extends MigrationAction
{

    public function description(): string
    {
        return "Vidage de la table TBL_CONTRAT pour permettre le changement au niveau des types de données";
    }



    public function utile(): bool
    {
        $ddl = $this->getBdd()->table()->get('TBL_CONTRAT')['TBL_CONTRAT'] ?? [];

        return ($ddl['columns']['EDITE']['precision'] ?? 1) > 1;
    }



    public function before(): void
    {
        $bdd = $this->getBdd();

        $bdd->exec('DELETE FROM TBL_CONTRAT');
    }

}