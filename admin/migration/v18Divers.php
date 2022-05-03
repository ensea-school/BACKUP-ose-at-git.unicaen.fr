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

        try {
            $c->msg('Suppression de la contrainte TYPE_INTERVENANT_CODE_UN en prévision de sa recréation');
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

        try {
            $c->msg('Coupure forcée de la synchronisation sur la table INTERVENANT');
            $bdd->exec("UPDATE IMPORT_TABLES SET SYNC_ENABLED = 0 WHERE TABLE_NAME = 'INTERVENANT'");
        } catch (\Exception $e) {
            // rien à faire : la contrainte a déjà du être supprimée
        }
    }

}