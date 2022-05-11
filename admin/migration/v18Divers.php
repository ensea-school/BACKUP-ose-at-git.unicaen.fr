<?php





class v18Divers extends AbstractMigration
{

    public function description(): string
    {
        return "Migration OSE 17 vers OSE 18";
    }



    public function utile(): bool
    {
        return true;
    }



    public function before()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

        // test pour savoir si on est bien en V17 minimum
        if (!$this->manager->hasColumn('INTERVENANT', 'EXPORT_DATE')) {
            $c->printDie('Attention : vous devez d\'abord mettre à jour en version 17.3 AVANT de mettre à jour en version 18');
        }

        if (!$this->checkDoublons()) {
            $c->printDie('Attention : des doublons ont été trouvés dans vos intervenants. Merci de purger d\'abord votre base de données, puis de relancer ensuite la procédure de mise à jour.');
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



    public function checkDoublons(): bool
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

        $need = $this->manager->hasNew(\BddAdmin\Ddl\Ddl::UNIQUE_CONSTRAINT, 'INTERVENANT_CODE_UN')
            || $this->manager->hasNew(\BddAdmin\Ddl\Ddl::UNIQUE_CONSTRAINT, 'INTERVENANT_SOURCE_UN')
            || $this->manager->hasNew(\BddAdmin\Ddl\Ddl::UNIQUE_CONSTRAINT, 'INTERVENANT_UTIL_CODE_UN');

        if (!$need) return true;

        $statutTable = $this->manager->hasTable('STATUT') ? 'STATUT' : 'STATUT_INTERVENANT';

        $sql = "SELECT
          COALESCE(t1.annee, t2.annee, t3.annee) annee,
          COALESCE(t1.nom, t2.nom, t3.nom) nom,
          COALESCE(t1.prenom, t2.prenom, t3.prenom) prenom,
          COALESCE(t1.statut, t2.statut, t3.statut) statut,
          CASE WHEN t1.cc IS NULL THEN '' ELSE t1.c || ' identiques' END CODE,
          CASE WHEN t2.cc IS NULL THEN '' ELSE t2.c || ' identiques' END SOURCE_CODE,
          CASE WHEN t3.cc IS NULL THEN '' ELSE t3.c || ' identiques' END UTILISATEUR_CODE
        FROM
          (
            SELECT
              i.ANNEE_ID annee, 
              i.nom_usuel nom,
              i.prenom prenom,
              si.libelle statut,
              'code_un' cc,
              count(*) c
            FROM
              intervenant i
              JOIN $statutTable si ON si.id = i.statut_id
            WHERE
              i.HISTO_DESTRUCTION IS NULL
            GROUP BY
              i.CODE, 
              i.ANNEE_ID, 
              i.STATUT_ID,
              i.nom_usuel,
              i.prenom,
              si.libelle
            HAVING 
              COUNT(*) > 1
          ) t1

          FULL JOIN (
            SELECT
              i.ANNEE_ID annee,
              i.nom_usuel nom,
              i.prenom prenom,
              si.libelle statut,
              'source_un' cc,
              count(*) c
            FROM
              intervenant i
              JOIN $statutTable si ON si.id = i.statut_id
            WHERE
              i.HISTO_DESTRUCTION IS NULL
            GROUP BY
              i.SOURCE_CODE, 
              i.ANNEE_ID, 
              i.STATUT_ID,
              i.nom_usuel,
              i.prenom,
              si.libelle
            HAVING COUNT(*) > 1
          ) t2 ON t2.nom = t1.nom AND t2.prenom = t1.prenom AND t2.statut = t1.statut

          FULL JOIN (
            SELECT
              i.ANNEE_ID annee,
              i.nom_usuel nom,
              i.prenom prenom,
              si.libelle statut,
              'util_code_un' cc,
              count(*) c
            FROM
              intervenant i
              JOIN $statutTable si ON si.id = i.statut_id
            WHERE
              i.HISTO_DESTRUCTION IS NULL
              AND i.UTILISATEUR_CODE IS NOT NULL
            GROUP BY
              i.UTILISATEUR_CODE, 
              i.ANNEE_ID, 
              i.STATUT_ID,
              i.nom_usuel,
              i.prenom,
              si.libelle
            HAVING COUNT(*) > 1
          ) t3 ON t3.nom = COALESCE(t1.nom,t2.nom) AND t3.prenom = COALESCE(t1.prenom,t2.prenom) AND t3.statut = COALESCE(t1.statut,t2.statut)
        ";

        $res = $bdd->select($sql);

        if (!empty($res)) {
            $c->printArray($res);
            $c->println('Des intervenants ayant le même statut, le même source_code ou le même utilisateur_code pour la même année ont été trouvés.');
            $c->println('Merci de supprimer ou modifier manuellement les intervenants concernés AVANT de poursuivre la mise à jour.');
            $c->println('Une fois le traitement effectué, vous pourrez reprendre la mise à jour via la commande ./bin/ose update-bdd.');

            return false;
        } else {
            return true;
        }
    }

}