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

        try {
            $bdd->exec("alter trigger F_INTERVENANT disable");
        } catch (\Exception $e) {
        }

        try {
            $bdd->exec("alter trigger F_INTERVENANT_S disable");
        } catch (\Exception $e) {
        }

        try {
            $c->msg('Coupure forcée de la synchronisation sur la table INTERVENANT');
            $bdd->exec("UPDATE IMPORT_TABLES SET SYNC_ENABLED = 0 WHERE TABLE_NAME = 'INTERVENANT'");
        } catch (\Exception $e) {
            // rien à faire : la contrainte a déjà du être supprimée
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

        $uc = \BddAdmin\Ddl\Ddl::UNIQUE_CONSTRAINT;

        $res = [];
        if ($this->manager->hasNew($uc, 'INTERVENANT_CODE_UN')) {
            $lres = $this->checkDoublonsCode();
            foreach ($lres as $r) {
                $res[] = $r;
            }
        }

        if ($this->manager->hasNew($uc, 'INTERVENANT_SOURCE_UN')) {
            $lres = $this->checkDoublonsSourceCode();
            foreach ($lres as $r) {
                $res[] = $r;
            }
        }

        $data     = [];
        $sqls     = [];
        $autoSqls = [];
        foreach ($res as $i) {
            if ($i['HISTO_DESTRUCTION']) {
                $autoSqls[$i['USQL']] = $i['USQL'];
            } else {
                if (isset($i['CODE'])) {
                    $cl = 'Code';
                    $cv = $i['CODE'];
                } elseif (isset($i['SOURCE_CODE'])) {
                    $cl = 'Code Source';
                    $cv = $i['SOURCE_CODE'];
                } elseif (isset($i['UTILISATEUR_CODE'])) {
                    $cl = 'Code Utilisateur';
                    $cv = $i['UTILISATEUR_CODE'];
                } else {
                    $cl = 'Code inconnu';
                    $cv = null;
                }

                $data[$i['USQL']] = [
                    'Année'     => $i['ANNEE_ID'],
                    $cl         => $cv,
                    'STATUT'    => $i['STATUT'],
                    'Nom usuel' => $i['NOM_USUEL'],
                    'Prénom'    => $i['PRENOM'],
                ];
                $sqls[$i['USQL']] = $i['USQL'];
            }
        }

        foreach ($autoSqls as $sql) {
            $bdd->exec($sql);
        }

        if (!empty($data)) {
            $c->printArray($data);
            $c->println('Des intervenants ayant deux fois le même statut, le même source_code ou le même utilisateur_code pour la même année ont été trouvés.');
            $c->println('Afin de régler ce problème, vous devrez historiser les fiches concernées en exécutant vous-mêmes en BDD les requêtes suivantes :');
            $c->println('');
            $c->println(implode("\n", $sqls));
            $c->println('');
            $c->println('Une fois le traitement effectué, vous pourrez reprendre la mise à jour via la commande ./bin/ose update-bdd.');

            return false;
        } else {
            return true;
        }
    }



    private function checkDoublonsCode(): array
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

        $statutTable = $this->manager->hasTable('STATUT') ? 'STATUT' : 'STATUT_INTERVENANT';

        $sql = "
        SELECT
          i.*,
          i.nom_usuel nom,
          i.prenom prenom,
          si.libelle statut,
          CASE WHEN i.histo_destruction IS NOT NULL THEN
            'UPDATE INTERVENANT SET HISTO_DESTRUCTION = HISTO_DESTRUCTION + interval ''1'' second WHERE ID = ' || i.id
          ELSE
            'UPDATE INTERVENANT SET HISTO_DESTRUCTION = sysdate, HISTO_DESTRUCTEUR_ID = ose_parametre.get_ose_user WHERE ID = ' || i.id || ';'
          END usql
        FROM
          (
          SELECT
            i.*,
            min(nbdeps) OVER (PARTITION BY code, annee_id, statut_id, histo_destruction) minnbdeps,
            max(nbdeps) OVER (PARTITION BY code, annee_id, statut_id, histo_destruction) maxnbdeps,
            ROW_NUMBER() OVER (PARTITION BY code, annee_id, statut_id, histo_destruction ORDER BY id) ordre
          FROM
            (
              SELECT
                code, annee_id, statut_id, histo_destruction
                ,id,prenom,nom_usuel,
                (
                    (SELECT count(*) FROM SERVICE_REFERENTIEL WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM VALIDATION WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM AGREMENT WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM CONTRAT WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM HISTO_INTERVENANT_SERVICE WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM AFFECTATION_RECHERCHE WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM PIECE_JOINTE WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM SERVICE WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM MODIFICATION_SERVICE_DU WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM INDIC_MODIF_DOSSIER WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM INTERVENANT_PAR_DEFAUT WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM INTERVENANT_DOSSIER WHERE intervenant_id = i.id)
                ) nbdeps,
                COUNT(*) OVER (PARTITION BY code, annee_id, statut_id, histo_destruction) nbr
              FROM
                intervenant i
            ) i
          WHERE 
            nbr > 1
          ) i
          JOIN $statutTable si ON si.id = i.statut_id
        WHERE
          CASE 
            WHEN nbdeps < maxnbdeps AND nbdeps = minnbdeps THEN 1
            WHEN i.ordre > 1 THEN 1
            ELSE 0
          END = 1
        ";

        return $bdd->select($sql);
    }



    private function checkDoublonsSourceCode(): array
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

//        $statutTable = $this->manager->hasTable('STATUT') ? 'STATUT' : 'STATUT_INTERVENANT';
        $statutTable = 'STATUT_INTERVENANT';

        $sql = "
        SELECT
          i.*,
          i.nom_usuel nom,
          i.prenom prenom,
          si.libelle statut,
          CASE WHEN i.histo_destruction IS NOT NULL THEN
            'UPDATE INTERVENANT SET HISTO_DESTRUCTION = HISTO_DESTRUCTION + interval ''1'' second WHERE ID = ' || i.id
          ELSE
            'UPDATE INTERVENANT SET HISTO_DESTRUCTION = sysdate, HISTO_DESTRUCTEUR_ID = ose_parametre.get_ose_user WHERE ID = ' || i.id || ';'
          END usql
        FROM
          (
          SELECT
            i.*,
            min(nbdeps) OVER (PARTITION BY source_code, annee_id, statut_id, histo_destruction) minnbdeps,
            max(nbdeps) OVER (PARTITION BY source_code, annee_id, statut_id, histo_destruction) maxnbdeps,
            ROW_NUMBER() OVER (PARTITION BY source_code, annee_id, statut_id, histo_destruction ORDER BY id) ordre
          FROM
            (
              SELECT
                source_code, annee_id, statut_id, histo_destruction
                ,id,prenom,nom_usuel,
                (
                    (SELECT count(*) FROM SERVICE_REFERENTIEL WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM VALIDATION WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM AGREMENT WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM CONTRAT WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM HISTO_INTERVENANT_SERVICE WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM AFFECTATION_RECHERCHE WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM PIECE_JOINTE WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM SERVICE WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM MODIFICATION_SERVICE_DU WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM INDIC_MODIF_DOSSIER WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM INTERVENANT_PAR_DEFAUT WHERE intervenant_id = i.id)
                  + (SELECT count(*) FROM INTERVENANT_DOSSIER WHERE intervenant_id = i.id)
                ) nbdeps,
                COUNT(*) OVER (PARTITION BY source_code, annee_id, statut_id, histo_destruction) nbr
              FROM
                intervenant i
            ) i
          WHERE 
            nbr > 1
          ) i
          JOIN $statutTable si ON si.id = i.statut_id
        WHERE
          CASE 
            WHEN nbdeps < maxnbdeps AND nbdeps = minnbdeps THEN 1
            WHEN i.ordre > 1 THEN 1
            ELSE 0
          END = 1
        ";

        return $bdd->select($sql);
    }

}