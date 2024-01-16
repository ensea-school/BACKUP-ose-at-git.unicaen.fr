<?php





class v23ModeEnseignement extends AbstractMigration
{

    public function description (): string
    {
        return "Migration/Fiabilisation des modes de saisie (semestriel ou calendaire) au niveau des statuts";
    }



    public function utile (): bool
    {
        $c   = $this->manager->getOseAdmin()->console();
        $bdd = $this->manager->getBdd();

        //Si j'ai une nouvelle colonne de paramétrage du mode d'enseignement sur la table statut
        if ($this->manager->hasNewColumn('STATUT', 'MODE_ENSEIGNEMENT_REALISE')) {
            return true;
        }

        //Sinon j'ai dejà le paramètrage du mode de saisie sur la table statut mais que j'ai des valeurs à null
        if ($this->manager->hasColumn('STATUT', 'MODE_ENSEIGNEMENT_REALISE')) {
            $result = $bdd->select("SELECT * FROM statut where mode_enseignement_previsionnel IS NULL OR mode_enseignement_realise IS NULL");
            if (!empty($result)) {
                return true;
            }
        }

        //Sinon pas besoin d'executer le script de migration
        return false;
    }



    public function before ()
    {

        $c   = $this->manager->getOseAdmin()->console();
        $bdd = $this->manager->getBdd();

        //Si la colonne mode de saisie de service existe déjà sur la table statut (migration après V20)
        if ($this->manager->hasColumn('STATUT', 'MODE_ENSEIGNEMENT_REALISE')) {
            //On regarde si sur des statuts la valeur de mode_enseignement_prevision ou mode_enseignement_realise est NULL
            $result = $bdd->select("SELECT * FROM statut where mode_enseignement_previsionnel IS NULL OR mode_enseignement_realise IS NULL");
            if (!empty($result)) {
                $c->println("Statut avec des modes de saisie de service à NULL, mise à jour en semstriel par défaut");
                //On force le mode semestriel à la place de NULL
                $bdd->exec("update statut set mode_enseignement_previsionnel = 'semestriel' where mode_enseignement_previsionnel IS NULL");
                $bdd->exec("update statut set mode_enseignement_realise = 'semestriel' where mode_enseignement_realise IS NULL");
            }
        }
    }



    public function after ()
    {
        $c   = $this->manager->getOseAdmin()->console();
        $bdd = $this->manager->getBdd();
        //Si on vient d'ajouter la colonne mode de saisie sur la table statut
        if ($this->manager->hasNewColumn('STATUT', 'MODE_ENSEIGNEMENT_REALISE')) {
            $c->println("Nouveau paramètrage de mode de saisie de service sur la table STATUT ");
            $c->println("Sauvegarde de la table paramètres");
            //Sauvegarde de la table paramètre
            $this->manager->sauvegarderTable('PARAMETRE', 'SAVE_PARAMETRE');
            //On récupère le paramètrage par défaut des modes de saisi des enseignements
            $modes = $bdd->select("SELECT nom, valeur FROM SAVE_PARAMETRE WHERE nom IN ('modalite_services_prev_ens', 'modalite_services_real_ens')");
            foreach ($modes as $mode) {
                //On met à jour le mode previsionnel au niveau des statuts
                if ($mode['NOM'] == 'modalite_services_prev_ens') {
                    $val = $mode['VALEUR'];
                    $c->println('Mise à jour du mode prévisionnel sur les statuts : ' . $val);
                    $sqlUpdate = "UPDATE statut SET MODE_ENSEIGNEMENT_PREVISIONNEL  = '" . $val . "'";
                    $c->println($sqlUpdate);
                    $bdd->exec($sqlUpdate);
                }
                //On met à jour le mode realiser au niveau des statuts
                if ($mode['NOM'] == 'modalite_services_real_ens') {
                    $val = $mode['VALEUR'];
                    $c->println('Mise à jour du mode prévisionnel sur les statuts : ' . $val);
                    $sqlUpdate = "UPDATE statut SET MODE_ENSEIGNEMENT_REALISE  = '" . $val . "'";
                    $c->println($sqlUpdate);
                    $bdd->exec($sqlUpdate);
                }
            }
        }
    }
}