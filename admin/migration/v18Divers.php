<?php





class v18Divers extends AbstractMigration
{

    public function description(): string
    {
        return "Migration OSE 17 vers OSE 18";
    }



    public function utile(): bool
    {
        return $this->manager->hasNew('table', 'PLAFOND_PERIMETRE');
    }



    public function before()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

        // test pour savoir si on est bien en V17 minimum
        if (!$this->manager->hasColumn('INTERVENANT', 'EXPORT_DATE')) {
            $c->printDie('Attention : vous devez d\'abord mettre à jour en version 17.3 AVANT de mettre à jour en version 18');
        }

        try {
            $c->msg('Coupure forcée de la synchronisation sur la table INTERVENANT');
            $bdd->exec("UPDATE IMPORT_TABLES SET SYNC_ENABLED = 0 WHERE TABLE_NAME = 'INTERVENANT'");
        } catch (\Exception $e) {
            // rien à faire : la contrainte a déjà du être supprimée
        }

        $this->sauvegardes();

        try {
            $c->msg('Suppression des affectations de recherche ayant des structures invalides');
            $bdd->exec("DELETE FROM AFFECTATION_RECHERCHE WHERE structure_id NOT IN (SELECT ID FROM STRUCTURE)");
        } catch (\Exception $e) {
            // rien à faire : la contrainte a déjà du être supprimée
        }

        try {
            $c->msg('Suppression de la contrainte TYPE_INTERVENANT_CODE_UN en prévision de sa recréation');
            $bdd->exec("ALTER TABLE TYPE_INTERVENANT DROP CONSTRAINT TYPE_INTERVENANT_CODE_UN");
        } catch (\Exception $e) {
            // rien à faire : la contrainte a déjà du être supprimée
        }
    }



    protected function sauvegardes()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

        $tables = [
            'INTERVENANT'                    => 'SAVE_V18_INTERVENANT',
            'INTERVENANT_DOSSIER'            => 'SAVE_V18_DOSSIER',
            'TYPE_PIECE_JOINTE_STATUT'       => 'SAVE_V18_TPJS',
            'TYPE_INTERVENTION_STATUT'       => 'SAVE_V18_TIS',
            'STATUT_INTERVENANT'             => 'SAVE_V18_STATUT',
            'PRIVILEGE'                      => 'SAVE_V18_PRIVILEGE',
            'ROLE_PRIVILEGE'                 => 'SAVE_V18_ROLE_PRIVILEGE',
            'STATUT_PRIVILEGE'               => 'SAVE_V18_STATUT_PRIVILEGE',
            'TYPE_AGREMENT_STATUT'           => 'SAVE_V18_TA_STATUT',
            'DOSSIER_CHAMP_AUTRE_PAR_STATUT' => 'SAVE_V18_DOSSIER_AUTRE_STATUT',
            'STRUCTURE'                      => 'SAVE_V18_STRUCTURE',
            'FONCTION_REFERENTIEL'           => 'SAVE_V18_REFERENTIEL',
            'PLAFOND_APPLICATION'            => 'SAVE_V18_PLAFOND_APP',
            'PLAFOND'                        => 'SAVE_V18_PLAFOND',
        ];

        foreach ($tables as $table => $saveTable) {
            if (!$this->manager->hasTable($table) || $this->manager->hasTable($saveTable)) {
                unset($tables[$table]);
            }
        }

        $c->begin('Sauvegarde des anciennes données');
        foreach ($tables as $table => $saveTable) {
            $c->msg('Table "' . $table . '" sauvegardée en "' . $saveTable . '".');
            $this->manager->sauvegarderTable($table, $saveTable);
        }
        $c->end();
    }

}