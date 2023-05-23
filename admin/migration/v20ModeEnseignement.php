<?php





class v20ModeEnseignement extends AbstractMigration
{

    public function description(): string
    {
        return "Migration des modes de saisie (semestriel ou calendaire) au niveau des statuts";
    }



    public function utile(): bool
    {
        return $this->manager->hasNewColumn('STATUT', 'MODE_REFERENTIEL_REALISE');
    }



    public function before()
    {


        $c   = $this->manager->getOseAdmin()->getConsole();
        $bdd = $this->manager->getBdd();

        $c->println("Sauvegarde de la table paramètres");
        //Sauvegarde de la table paramètre
        $this->manager->sauvegarderTable('PARAMETRE', 'SAVE_PARAMETRE');
    }



    public function after()
    {
        $c   = $this->manager->getOseAdmin()->getConsole();
        $bdd = $this->manager->getBdd();
        //On récupère le paramètrage par défaut des modes de saisi des enseignements
        $modes = $bdd->select("SELECT nom, valeur FROM SAVE_PARAMETRE WHERE nom IN ('modalite_services_prev_ens', 'modalite_services_real_ens')");
        //$c->printArray($modes);
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

        $c->println("Fin migration des modes de saisie sur le statut");
    }
}