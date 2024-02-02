<?php

class v23UpdateStructures extends AbstractMigration
{

    public function description(): string
    {
        return "Ajout du script d'après-déclenchement de MAJ de la table STRUCTURE";
    }



    public function utile(): bool
    {
        $action = $this->manager->getBdd()->selectOne("SELECT SYNC_HOOK_AFTER FROM IMPORT_TABLES WHERE TABLE_NAME = 'STRUCTURE'", [], 'SYNC_HOOK_AFTER');

        return '' == $action;
    }



    public function after()
    {
        $sql = "UPDATE IMPORT_TABLES SET SYNC_HOOK_AFTER = 'OSE_DIVERS.UPDATE_STRUCTURES();' WHERE TABLE_NAME = 'STRUCTURE'";
        $this->manager->getBdd()->exec($sql);
    }
}