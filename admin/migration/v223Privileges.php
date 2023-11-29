<?php

class v223Privileges extends AbstractMigration
{

    public function description(): string
    {
        return "Mise à jour de l'état de sortie de synthèse des privilèges pour se brancher sur la nouvelle vue";
    }



    public function utile(): bool
    {
        return $this->manager->hasNew('view', 'V_SYNTHESE_PRIVILEGE');
    }



    public function after()
    {
        $sql = "UPDATE etat_sortie SET REQUETE = 'SELECT * FROM v_synthese_privilege' WHERE code = 'synthese-privilege'";
        $this->manager->getBdd()->exec($sql);
    }
}